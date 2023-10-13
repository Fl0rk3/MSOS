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
</head>

<body>
    <div class="header">MSOS</div>
    <div class="loginBox">
        <div class="loginBox_form">
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
        <div class="register"></div>
    </div>
</body>

</html>