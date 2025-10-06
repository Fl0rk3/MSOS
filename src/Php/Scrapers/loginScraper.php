<?php

namespace MSOS\Php\Scrapers;

use MSOS\Php\Login;
use MSOS\Php\Queries\LoginQuery;

session_start();

$login = $_POST['login'];
$password = $_POST['password'];

$loginQuery = new LoginQuery();
$checkUser = $loginQuery->loginUser($login, $password);

if (!is_null($checkUser)) {
    $_SESSION['userData'] = new Login($checkUser[0]['user_id'], $checkUser[0]['username'], $checkUser[0]['is_admin']);
    $_SESSION['is_logged'] = true;
    header('Location: ./../../../public/index.php');
} else {
    $_SESSION['errorLogin'] = "Błędne dane logowania.";
    header('Location: ./../../../public/Sites/home.php');
}
