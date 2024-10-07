<?php
include_once('./php/Login.php');

session_start();

if (!isset($_SESSION['is_logged'])) {
    header("Location: ./sites/home.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSOS - panel studenta</title>

    <link rel="stylesheet" href="./sass/layout.css">
    <link rel="stylesheet" href="./sass/index.css">
    <link rel="stylesheet" href="./sass/optionsBox.css">

    <script src="./javascript/timer.js"></script>
    <script src="./javascript/optionsBox.js"></script>
    <script src="https://kit.fontawesome.com/9eef710565.js" crossorigin="anonymous"></script>
</head>

<body onload="display_time(); optionsBox();">
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
                    echo 'Użytkownik: ' . $_SESSION['userData']->getUsername() . '<br/>';
                    ?>
                </div>

                <div class="right_nav_user_logout">
                    <a href="./php/scrapers/logout.php" id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
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
                    echo $_SESSION['userData']->getLinks()->getLinksToHTML($_SESSION['userData']->getUserLinks());
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
        Florian Ficek &copy; 2023
    </div>
    <div class="option_window" id="option_window">
        <div class="option_window_box option_window_addLink" id="addLink_box">
            <div class="option_window_box_close" id="addLink_close"><i class="fa-solid fa-xmark"></i></div>
            <div class="option_window_addLink_header">Dodawanie linka</div>

            <input type="text" name="addLink_name" id="addLink_name" placeholder="Nazwa URL">
            <input type="text" name="addLink_link" id="addLink_link" placeholder="URL">
            <input type="button" value="Dodaj" id="addLink_button">
        </div>
    </div>

    <div class="alerts_display" id="alerts_display"></div>

    <script src="./ajax/linksajax.js"></script>
</body>

</html>