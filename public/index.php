<?php

use Doctrine\ORM\Exception\ORMException;
use MSOS\backend\Constants\SettingConstants;
use MSOS\backend\ctrl\LinksRenderer;
use MSOS\backend\ctrl\SettingsRenderer;
use MSOS\backend\Provider\Links\DoctrineLinksProvider;
use MSOS\backend\Provider\Settings\DoctrineSettingsProvider;

session_start();

if (!isset($_SESSION['is_logged'])) {
    header("Location: ./Sites/home.Php");
}

$user = $_SESSION['userData'] ?? null;

/** @var Doctrine\ORM\EntityManagerInterface $em */
$em = require __DIR__ . '/../src/Config/bootstrap.php';

$linksProvider = new DoctrineLinksProvider($em);
try {
    $links = $linksProvider->forUser($user['id']);
} catch (ORMException $e) {

}

$settingsProvider = new DoctrineSettingsProvider($em);
try {
    $user_settings = $settingsProvider->forUser($user['id']);
} catch (ORMException $e) {

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSOS - panel studenta</title>

    <link rel="stylesheet" href="../assets/Sass/layout.css">
    <link rel="stylesheet" href="../assets/Sass/index.css">
    <link rel="stylesheet" href="../assets/Sass/popUps.css">

    <script>
        window.APP_LOCALE = <?= json_encode($user_settings[SettingConstants::SETTING_SYSTEM_LANGUAGE]) ?>;
    </script>
    <script src="../assets/Javascript/i18n.js" defer></script>
    <script src="../assets/Javascript/timer.js"></script>
    <script src="../assets/Javascript/popUps.js"></script>
    <script src="https://kit.fontawesome.com/9eef710565.js" crossorigin="anonymous"></script>
</head>

<body onload="display_time('<?= $user_settings[SettingConstants::SETTING_TIME_FORMAT] ?>'); linksPopUp(); settingsPopUp();">
<div class="nav">
    <div class="logo" data-i18n="common.title"></div>

    <div class="nav_bar">
        <div class="nav_bar_field"><a href="" data-i18n="home.subjects"></a></div>
    </div>

    <div id="time"></div>
</div>

<div class="container">
    <div class="calendar">
        <div class="calendar_nav">
            <div class="calendar_nav_header">
                <div class="calendar_nav_header_field" id="homeworks" data-i18n="home.homeworks"></div>
                <div class="calendar_nav_header_field" id="exams" data-i18n="home.exams"></div>
                <div class="calendar_nav_header_field" id="notes" data-i18n="home.notes"></div>
            </div>

            <div class="calendar_nav_bar">
                <div class="calendar_nav_bar_field" data-i18n="global.add"></div>
            </div>
        </div>
        <div class="calendar_list">
            <div class="calendar_list_header">
                <div class="calendar_list_header_field"></div>
                <div class="calendar_list_header_field" data-i18n="home.subject"></div>
                <div class="calendar_list_header_field" data-i18n="home.description"></div>
                <div class="calendar_list_header_field" data-i18n="global.date"></div>
            </div>

            <div class="calendar_list_fields">

            </div>
        </div>
    </div>

    <div class="right_nav">
        <div class="right_nav_user">
            <div class="right_nav_user_name">
                <span data-i18n="global.user"></span>:
                <?=
                $user['username'] . '<br/>';
                ?>
            </div>

            <div class="right_nav_user_buttons">
                <div class="right_nav_user_buttons_settings" id="viewSettings">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <div class="right_nav_user_buttons_logout">
                    <a href="../src/backend/Scraper/logout.php" id="logout"><i
                                class="fa-solid fa-right-from-bracket"></i></a>
                </div>
            </div>
        </div>

        <hr class="right_nav_hr">

        <div class="right_nav_links">
            <div class="right_nav_links_main">
                <div class="right_nav_links_main_title" data-i18n="home.useful_urls"></div>
                <div class="right_nav_links_main_add" id="addLink"><i class="fa-solid fa-plus"></i></div>
            </div>

            <div class="right_nav_links_bar" id="right_nav_links_bar">
                <?=
                LinksRenderer::toHtml($links);
                ?>
            </div>
        </div>

        <hr class="right_nav_hr">

        <div class="right_nav_schedule">
            <div class="right_nav_schedule_title" data-i18n="home.upcoming_classes"></div>
        </div>
    </div>
</div>

<div class="footer">
    Florian Ficek &copy; 2023-2025
</div>
<div class="option_window" id="option_window">
    <div class="option_window_box option_window_addLink" id="addLink_box">
        <div class="option_window_box_close" id="addLink_close"><i class="fa-solid fa-xmark"></i></div>
        <div class="option_window_addLink_header" data-i18n="option.add_url"></div>

        <input type="text" name="addLink_name" id="addLink_name" data-i18n-attr="placeholder:option.add_url.url_name"
               data-i18n-placeholder="option.add_url.url_name">
        <input type="text" name="addLink_link" id="addLink_link" data-i18n-attr="placeholder:option.add_url.url"
               data-i18n-placeholder="option.add_url.url">
        <input type="button" value="Dodaj" id="addLink_button">
    </div>

    <div class="option_window_box option_window_viewSettings" id="viewSettings_box">
        <div class="option_window_box_close" id="viewSettings_close"><i class="fa-solid fa-xmark"></i></div>
        <div class="option_window_viewSettings_header" data-i18n="option.settings.name"></div>
        <?=
        SettingsRenderer::toHtml($user_settings);
        ?>
        <input type="button" id="viewSettings_button" data-i18n-attr="value:global.save">
    </div>
</div>

<div class="alerts_display" id="alerts_display"></div>

<script src="../src/Ajax/linksajax.js"></script>
<script src="../src/Ajax/settingsajax.js"></script>
</body>

</html>