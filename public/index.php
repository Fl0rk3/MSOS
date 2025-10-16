<?php

session_start();

if (!isset($_SESSION['is_logged'])) {
    header("Location: ./Sites/home.Php");
}

$user = $_SESSION['userData'] ?? null;

/** @var Doctrine\ORM\EntityManagerInterface $em */
$em = require __DIR__ . '/../src/Config/bootstrap.php';

$linksProvider = new \MSOS\backend\Provider\Links\DoctrineLinksProvider($em);
$links = $linksProvider->forUser($user['id']);

$settingsProvider = new \MSOS\backend\Provider\Settings\DoctrineSettingsProvider($em);
$user_settings = $settingsProvider->forUser($user['id']);
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

    <script src="../assets/Javascript/timer.js"></script>
    <script src="../assets/Javascript/popUps.js"></script>
    <script src="https://kit.fontawesome.com/9eef710565.js" crossorigin="anonymous"></script>
</head>

<body onload="display_time(); linksPopUp(); settingsPopUp();">
<div class="nav">
    <div class="logo"><a href="#">MSOS</a></div>

    <div class="nav_bar">
        <div class="nav_bar_field"><a href="">Przedmioty</a></div>
        <div class="nav_bar_field"><a href="">Plan Zajęć</a></div>
    </div>

    <div id="time"></div>
</div>

<div class="container">
    <div class="calendar">
        <div class="calendar_nav">
            <div class="calendar_nav_header">
                <div class="calendar_nav_header_field" id="homework">Domowe</div>
                <div class="calendar_nav_header_field" id="exams">Kolokwia</div>
                <div class="calendar_nav_header_field" id="notes">Notatki</div>
            </div>

            <div class="calendar_nav_bar">
                <div class="calendar_nav_bar_field">Dodaj</div>
            </div>
        </div>
        <div class="calendar_list">
            <div class="calendar_list_header">
                <div class="calendar_list_header_field"></div>
                <div class="calendar_list_header_field">Przedmiot</div>
                <div class="calendar_list_header_field">Opis</div>
                <div class="calendar_list_header_field">Data</div>
            </div>

            <div class="calendar_list_fields">

            </div>
        </div>
    </div>

    <div class="right_nav">
        <div class="right_nav_user">
            <div class="right_nav_user_name">
                <?php
                echo 'Użytkownik: ' . $user['username'] . '<br/>';
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
                <div class="right_nav_links_main_title">Przydatne linki</div>
                <div class="right_nav_links_main_add" id="addLink"><i class="fa-solid fa-plus"></i></div>
            </div>

            <div class="right_nav_links_bar" id="right_nav_links_bar">
                <?php
                echo \MSOS\backend\ctrl\LinksRenderer::toHtml($links);
                ?>
            </div>
        </div>

        <hr class="right_nav_hr">

        <div class="right_nav_schedule">
            <div class="right_nav_schedule_title">Najbliższe zajęcia:</div>
        </div>
    </div>
</div>

<div class="footer">
    Florian Ficek &copy; 2023-2025
</div>
<div class="option_window" id="option_window">
    <div class="option_window_box option_window_addLink" id="addLink_box">
        <div class="option_window_box_close" id="addLink_close"><i class="fa-solid fa-xmark"></i></div>
        <div class="option_window_addLink_header">Dodawanie linka</div>

        <input type="text" name="addLink_name" id="addLink_name" placeholder="Nazwa URL">
        <input type="text" name="addLink_link" id="addLink_link" placeholder="URL">
        <input type="button" value="Dodaj" id="addLink_button">
    </div>

    <div class="option_window_box option_window_viewSettings" id="viewSettings_box">
        <div class="option_window_box_close" id="viewSettings_close"><i class="fa-solid fa-xmark"></i></div>
        <div class="option_window_viewSettings_header">User settings</div>
        <?php
        echo \MSOS\backend\ctrl\SettingsRenderer::toHtml($user_settings);
        ?>
        <input type="button" value="Save" id="viewSettings_save">
    </div>
</div>

<div class="alerts_display" id="alerts_display"></div>

<script src="../src/Ajax/linksajax.js"></script>
</body>

</html>