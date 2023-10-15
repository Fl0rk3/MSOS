<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSOS</title>

    <link rel="stylesheet" href="./../sass/layout.css">
    <link rel="stylesheet" href="./../sass/home.css">

    <script src="./../javascript/loginRegisterChanger.js"></script>
</head>

<body onload="loginRegisterChanger()">
    <div class="header">MSOS</div>
    <div class="loginBox">
        <div class="loginBox_selector">
            <div class="loginBox_selector_field loginBox_selector_field_login" id="selected">Zaloguj</div>
            <div class="loginBox_selector_field loginBox_selector_field_register">Zarejestruj</div>
        </div>
        <div class="loginBox_form" id="loginBox_login">
            <form action="../php/scrapers/loginScraper.php" method="post">
                <label>Login: <input type="text" name="login" id="login"></label>
                <label>Hasło: <input type="password" name="password" id="password"></label>
                <input type="submit" value="Zaloguj">
                <?php
                if (isset($_SESSION['errorLogin'])) {
                    echo "<span id='error'>" . $_SESSION['errorLogin'] . "</span>";
                }
                ?>
            </form>
        </div>
        <div class="loginBox_form" id="loginBox_register">
            <form action="../php/scrapers/registerScraper.php" method="post">
                <label>Login: <input type="text" name="login" id="login_reg"></label>
                <label>Hasło: <input type="password" name="password" id="password_reg"></label>
                <label>Powtórz hasło: <input type="password" name="password_repeat" id="password_repeat"></label>
                <input type="submit" value="Zarejestruj">
            </form>
        </div>
    </div>
</body>

</html>