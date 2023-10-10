<?php
session_start();

require_once("./php/queries/SubjectQuery.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSOS</title>

    <link rel="stylesheet" href="./sass/layout.css">
    <link rel="stylesheet" href="./sass/index.css">

    <script src="./javascript/timer.js"></script>
</head>

<body onload=display_time();>
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
                <div class="calendar_nav_title">Ważne terminy:</div>

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
            <div class="right_nav_links">
                <div class="right_nav_links_title">Przydatne linki:</div>

                <div class="right_nav_links_bar">
                    <div class="right_nav_links_bar_field" id="usos">
                        <a href="https://usosweb.wne.uw.edu.pl/" target="_blank">USOS</a>
                    </div>
                    <div class="right_nav_links_bar_field" id="moodle">
                        <a href="https://elearning.wne.uw.edu.pl" target="_blank">Moodle</a>
                    </div>
                    <div class="right_nav_links_bar_field" id="kampus">
                        <a href="https://kampus.come.uw.edu.pl" target="_blank">Kampus</a>
                    </div>
                </div>
            </div>

            <div class="right_nav_schedule">
                <div class="right_nav_schedule_title">Najbliższe zajęcia:</div>
            </div>
        </div>
    </div>

    <div class="footer">
        Florian Ficek &copy; 2023
    </div>
</body>

</html>