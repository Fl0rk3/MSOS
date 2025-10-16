<?php
session_start();

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MSOS</title>

        <link rel="stylesheet" href="../../assets/Sass/layout.css">
        <link rel="stylesheet" href="../../assets/Sass/home.css">

        <script src="../../assets/Javascript/loginRegisterChanger.js"></script>
    </head>

    <body onload="loginRegisterChanger()">
    <div class="header">MSOS</div>
    <div class="loginBox">
        <div class="loginBox_selector">
            <div class="loginBox_selector_field loginBox_selector_field_login selected" id="login_button">Sign In</div>
            <div class="loginBox_selector_field loginBox_selector_field_register" id="register_button">Sign Up</div>
        </div>
        <div class="loginBox_form loginBox_selected" id="loginBox_login">
            <form action="../../src/backend/Scraper/loginScraper.php" method="post">
                <input type="text" name="login" id="login" placeholder="Username" required maxlength="40">
                <input type="password" name="password" id="password" placeholder="Password" required maxlength="32">
                <input type="submit" value="Sign In">
                <?php
                if (isset($_SESSION['errorLogin'])) {
                    echo "<span id='error'>" . $_SESSION['errorLogin'] . "</span>";
                }
                ?>
            </form>
        </div>
        <div class="loginBox_form" id="loginBox_register">
            <form action="../../src/backend/Scraper/registerScraper.php" method="post">
                <input type="text" name="login" id="login_reg" placeholder="Username" required minlength="5"
                       maxlength="40">
                <input type="password" name="password" id="password_reg" placeholder="Password" required minlength="8"
                       maxlength="32">
                <input type="password" name="password_repeat" id="password_repeat" placeholder="Repeat password"
                       required minlength="8" maxlength="32">
                <input type="submit" value="Sign Up">
                <?php
                if (isset($_SESSION['errorRegister'])) {
                    echo "<span id='error'>" . $_SESSION['errorRegister'] . "</span>";
                }
                ?>
            </form>
        </div>
    </div>
    </body>

    </html>
<?php
unset($_SESSION['errorLogin']);
unset($_SESSION['errorRegister']);
?>