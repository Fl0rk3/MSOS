<?php
// apply_skeleton.php
$dsn = 'mysql:host=localhost;dbname=msos;charset=utf8mb4'; // dbname can be anything; script creates/uses your target DB inside
$user = 'root';
$pass = ''; // adjust

$sql = file_get_contents(__DIR__ . '/skeleton.sql');
if ($sql === false) {
    throw new RuntimeException('Could not read skeleton.sql');
}

$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // Important: we will execute statements one-by-one; no need for multi statements here
]);

/**
 * Split SQL script honoring DELIMITER changes (e.g., DELIMITER // ... //).
 * Returns an array of executable statements (without DELIMITER lines and without trailing delimiters).
 */
function split_sql_with_delimiters(string $sql): array
{
    $lines = preg_split("/\R/u", $sql);
    $delimiter = ';';
    $buffer = '';
    $stmts = [];

    foreach ($lines as $rawLine) {
        $line = $rawLine;

        // Skip BOM on first line
        if ($buffer === '' && str_starts_with($line, "\xEF\xBB\xBF")) {
            $line = substr($line, 3);
        }

        // Handle DELIMITER switches (client-side directive)
        if (preg_match('/^\s*DELIMITER\s+(.+)\s*$/i', $line, $m)) {
            // Flush any pending buffer if someone set delimiter mid-statement (rare)
            if (trim($buffer) !== '') {
                $stmts[] = rtrim($buffer);
                $buffer = '';
            }
            $delimiter = $m[1];
            continue;
        }

        // Accumulate line
        $buffer .= ($buffer === '' ? '' : "\n") . $rawLine;

        // If current buffer ends with the delimiter, cut a statement
        if ($delimiter === ';') {
            if (preg_match('/' . preg_quote($delimiter, '/') . '\s*$/', $buffer)) {
                $stmts[] = rtrim(substr($buffer, 0, -strlen($delimiter)));
                $buffer = '';
            }
        } else {
            // custom delimiter like //
            if (preg_match('/' . preg_quote($delimiter, '/') . '\s*$/', $buffer)) {
                $stmts[] = rtrim(substr($buffer, 0, -strlen($delimiter)));
                $buffer = '';
            }
        }
    }

    if (trim($buffer) !== '') {
        $stmts[] = rtrim($buffer);
    }

    // Remove empty / comment-only statements for safety
    $stmts = array_values(array_filter($stmts, function ($s) {
        $t = trim($s);
        if ($t === '') return false;
        // strip single-line comments and check again
        $t2 = preg_replace('/^\s*--.*$/m', '', $t);
        $t2 = preg_replace('#/\*.*?\*/#s', '', $t2);
        return trim($t2) !== '';
    }));

    return $stmts;
}

$statements = split_sql_with_delimiters($sql);

// Execute sequentially
foreach ($statements as $i => $stmt) {
    try {
        $pdo->exec($stmt);
    } catch (PDOException $e) {
        // Show which statement failed to help debugging
        throw new RuntimeException("SQL error at statement #" . ($i + 1) . ": " . $e->getMessage() . "\n\n" . $stmt, 0, $e);
    }
}

echo "Schema applied successfully.\n";