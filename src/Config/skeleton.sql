-- ======================
-- CONFIGURE ONCE
-- ======================
SET @DST_DB = 'msos';
SET @SRC_DB = '__blueprint';
-- temporary schema for desired structure

-- Make sure target DB exists
CREATE DATABASE IF NOT EXISTS `msos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Rebuild blueprint fresh each run (safe; contains only definitions)
DROP DATABASE IF EXISTS `__blueprint`;
CREATE DATABASE `__blueprint` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ==========================================================
-- >>> BLUEPRINT: DEFINE YOUR SCHEMA ONLY WITH CREATE TABLE <<<
-- Edit below anytime: add new tables or change columns/indexes.
-- ==========================================================
USE `__blueprint`;

CREATE TABLE `links`
(
    `link_id` int(8)       NOT NULL AUTO_INCREMENT,
    `user_id` int(8)       NOT NULL,
    `name`    varchar(30)  NOT NULL,
    `url`     varchar(100) NOT NULL,
    PRIMARY KEY (`link_id`),
    KEY `user_id` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `subjects`
(
    `subject_id` int(8)      NOT NULL AUTO_INCREMENT,
    `user_id`    int(8)      NOT NULL,
    `name`       varchar(40) NOT NULL,
    PRIMARY KEY (`subject_id`),
    KEY `user_id` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `users`
(
    `user_id`    int(8)       NOT NULL AUTO_INCREMENT,
    `username`   varchar(40)  NOT NULL,
    `password`   varchar(256) NOT NULL,
    `is_admin`   tinyint(1)   NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `username` (`username`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- ==========================================================
-- >>> SYNC ENGINE (Columns + PK + Indexes). Do not edit. <<<
-- ==========================================================
DELIMITER //

-- Build column definition text from information_schema
CREATE PROCEDURE `build_col_def`(IN p_schema VARCHAR(64), IN p_table VARCHAR(64), IN p_column VARCHAR(64),
                                 OUT p_def TEXT)
BEGIN
    DECLARE v_type TEXT; DECLARE v_null VARCHAR(3); DECLARE v_deflt TEXT; DECLARE v_extra TEXT;
    DECLARE v_dt VARCHAR(64);
    SELECT COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA, DATA_TYPE
    INTO v_type, v_null, v_deflt, v_extra, v_dt
    FROM information_schema.columns
    WHERE table_schema = p_schema
      AND table_name = p_table
      AND column_name = p_column;

    SET p_def = CONCAT('`', p_column, '` ', v_type, ' ', CASE WHEN v_null = 'NO' THEN 'NOT NULL' ELSE 'NULL' END);

    IF v_deflt IS NOT NULL THEN
        IF UPPER(v_deflt) LIKE 'CURRENT_TIMESTAMP%' THEN
            SET p_def = CONCAT(p_def, ' DEFAULT CURRENT_TIMESTAMP');
        ELSE
            IF v_dt IN ('char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'enum', 'set', 'json') THEN
                SET p_def = CONCAT(p_def, ' DEFAULT ''', REPLACE(v_deflt, '''', ''''''), '''');
            ELSE
                SET p_def = CONCAT(p_def, ' DEFAULT ', v_deflt);
            END IF;
        END IF;
    END IF;

    IF v_extra IS NOT NULL AND v_extra <> '' THEN
        IF LOCATE('on update current_timestamp', LOWER(v_extra)) > 0 THEN
            SET p_def = CONCAT(p_def, ' ON UPDATE CURRENT_TIMESTAMP');
        END IF;
        IF LOCATE('auto_increment', LOWER(v_extra)) > 0 THEN
            SET p_def = CONCAT(p_def, ' AUTO_INCREMENT');
        END IF;
    END IF;
END//

-- Decide if a column differs (type/null/default/extra)
CREATE FUNCTION `col_differs`(p_src VARCHAR(64), p_dst VARCHAR(64), p_table VARCHAR(64),
                              p_col VARCHAR(64)) RETURNS TINYINT(1)
    DETERMINISTIC
BEGIN
    DECLARE s_type TEXT; DECLARE d_type TEXT;
    DECLARE s_null VARCHAR(3); DECLARE d_null VARCHAR(3);
    DECLARE s_def TEXT; DECLARE d_def TEXT;
    DECLARE s_extra TEXT; DECLARE d_extra TEXT;

    SELECT COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA
    INTO s_type, s_null, s_def, s_extra
    FROM information_schema.columns
    WHERE table_schema = p_src
      AND table_name = p_table
      AND column_name = p_col;

    SELECT COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA
    INTO d_type, d_null, d_def, d_extra
    FROM information_schema.columns
    WHERE table_schema = p_dst
      AND table_name = p_table
      AND column_name = p_col;

    IF s_type <> d_type THEN RETURN 1; END IF;
    IF s_null <> d_null THEN RETURN 1; END IF;

    IF (s_def IS NULL) XOR (d_def IS NULL) THEN RETURN 1; END IF;
    IF s_def IS NOT NULL AND d_def IS NOT NULL THEN
        IF UPPER(s_def) LIKE 'CURRENT_TIMESTAMP%' THEN SET s_def = 'CURRENT_TIMESTAMP'; END IF;
        IF UPPER(d_def) LIKE 'CURRENT_TIMESTAMP%' THEN SET d_def = 'CURRENT_TIMESTAMP'; END IF;
        IF s_def <> d_def THEN RETURN 1; END IF;
    END IF;

    SET s_extra = LOWER(COALESCE(s_extra, '')); SET d_extra = LOWER(COALESCE(d_extra, ''));
    IF (LOCATE('auto_increment', s_extra) > 0) XOR (LOCATE('auto_increment', d_extra) > 0) THEN RETURN 1; END IF;
    IF (LOCATE('on update current_timestamp', s_extra) > 0) XOR
       (LOCATE('on update current_timestamp', d_extra) > 0) THEN
        RETURN 1;
    END IF;

    RETURN 0;
END//

-- Sync PRIMARY KEY if missing
CREATE PROCEDURE `sync_primary_key`(IN p_src VARCHAR(64), IN p_dst VARCHAR(64), IN p_table VARCHAR(64))
BEGIN
    DECLARE has_pk_src INT DEFAULT 0; DECLARE has_pk_dst INT DEFAULT 0; DECLARE cols TEXT;
    SELECT COUNT(*) > 0
    INTO has_pk_src
    FROM information_schema.table_constraints
    WHERE table_schema = p_src
      AND table_name = p_table
      AND constraint_type = 'PRIMARY KEY';

    SELECT COUNT(*) > 0
    INTO has_pk_dst
    FROM information_schema.table_constraints
    WHERE table_schema = p_dst
      AND table_name = p_table
      AND constraint_type = 'PRIMARY KEY';

    IF has_pk_src = 1 AND has_pk_dst = 0 THEN
        SELECT GROUP_CONCAT(CONCAT('`', k.column_name, '`') ORDER BY k.ordinal_position SEPARATOR ',')
        INTO cols
        FROM information_schema.key_column_usage k
                 JOIN information_schema.table_constraints c
                      ON c.table_schema = k.table_schema AND c.table_name = k.table_name AND
                         c.constraint_name = k.constraint_name
        WHERE k.table_schema = p_src
          AND k.table_name = p_table
          AND c.constraint_type = 'PRIMARY KEY';

        SET @sql := CONCAT('ALTER TABLE `', p_dst, '`.`', p_table, '` ADD PRIMARY KEY (', cols, ')');
        PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
    END IF;
END//

-- Add any index present in SRC but missing in DST (unique and non-unique)
CREATE PROCEDURE `sync_indexes`(IN p_src VARCHAR(64), IN p_dst VARCHAR(64), IN p_table VARCHAR(64))
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_idx VARCHAR(64); DECLARE v_nonunique INT; DECLARE v_cols TEXT;

    DECLARE cur CURSOR FOR
        SELECT s.index_name, MIN(s.non_unique)
        FROM information_schema.statistics s
        WHERE s.table_schema = p_src
          AND s.table_name = p_table
          AND s.index_name <> 'PRIMARY'
        GROUP BY s.index_name;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;
    idx_loop:
    LOOP
        FETCH cur INTO v_idx, v_nonunique;
        IF done = 1 THEN LEAVE idx_loop; END IF;

        IF NOT EXISTS (SELECT 1
                       FROM information_schema.statistics
                       WHERE table_schema = p_dst
                         AND table_name = p_table
                         AND index_name = v_idx) THEN
            SELECT GROUP_CONCAT(CONCAT('`', column_name, '`') ORDER BY seq_in_index SEPARATOR ',')
            INTO v_cols
            FROM information_schema.statistics
            WHERE table_schema = p_src
              AND table_name = p_table
              AND index_name = v_idx;

            SET @sql := CONCAT(
                    'CREATE ',
                    CASE WHEN v_nonunique = 0 THEN 'UNIQUE ' ELSE '' END,
                    'INDEX `', v_idx, '` ON `', p_dst, '`.`', p_table, '` (', v_cols, ')'
                        );
            PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
        END IF;
    END LOOP;
    CLOSE cur;
END//

-- Sync columns (add/modify) and ensure table exists
DROP PROCEDURE IF EXISTS `sync_table_columns`//
CREATE PROCEDURE `sync_table_columns`(IN p_src VARCHAR(64), IN p_dst VARCHAR(64), IN p_table VARCHAR(64))
proc:
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_col VARCHAR(64);

    DECLARE cur CURSOR FOR
        SELECT column_name
        FROM information_schema.columns
        WHERE table_schema = p_src
          AND table_name = p_table
        ORDER BY ordinal_position;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- If destination table is missing, create it from blueprint and exit procedure early
    IF NOT EXISTS (SELECT 1
                   FROM information_schema.tables
                   WHERE table_schema = p_dst
                     AND table_name = p_table) THEN
        SET @sql := CONCAT('CREATE TABLE `', p_dst, '`.`', p_table, '` LIKE `', p_src, '`.`', p_table, '`');
        PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

        LEAVE proc; -- << exit the labelled block (valid LEAVE target)
    END IF;

    OPEN cur;
    c_loop:
    LOOP
        FETCH cur INTO v_col;
        IF done = 1 THEN LEAVE c_loop; END IF;

        -- Add column if missing
        IF NOT EXISTS (SELECT 1
                       FROM information_schema.columns
                       WHERE table_schema = p_dst
                         AND table_name = p_table
                         AND column_name = v_col) THEN
            CALL build_col_def(p_src, p_table, v_col, @def_add);
            SET @sql := CONCAT('ALTER TABLE `', p_dst, '`.`', p_table, '` ADD COLUMN ', @def_add);
            PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

            -- Or modify if it differs
        ELSEIF col_differs(p_src, p_dst, p_table, v_col) = 1 THEN
            CALL build_col_def(p_src, p_table, v_col, @def_mod);
            SET @sql := CONCAT('ALTER TABLE `', p_dst, '`.`', p_table, '` MODIFY ', @def_mod);
            PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
        END IF;
    END LOOP;
    CLOSE cur;
END//

-- Sync all tables in blueprint: columns, PK, indexes
CREATE PROCEDURE `sync_all`(IN p_src VARCHAR(64), IN p_dst VARCHAR(64))
BEGIN
    DECLARE done INT DEFAULT 0; DECLARE v_table VARCHAR(64);
    DECLARE cur CURSOR FOR
        SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = p_src
          AND table_type = 'BASE TABLE';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Ensure destination exists
    SET @sql := CONCAT('CREATE DATABASE IF NOT EXISTS `', p_dst, '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    PREPARE s0 FROM @sql; EXECUTE s0; DEALLOCATE PREPARE s0;

    OPEN cur;
    t_loop:
    LOOP
        FETCH cur INTO v_table;
        IF done = 1 THEN LEAVE t_loop; END IF;

        CALL sync_table_columns(p_src, p_dst, v_table);
        CALL sync_primary_key(p_src, p_dst, v_table);
        CALL sync_indexes(p_src, p_dst, v_table);
    END LOOP;
    CLOSE cur;
END//

DELIMITER ;

-- Run the sync
CALL sync_all(@SRC_DB, @DST_DB);