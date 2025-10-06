<?php

namespace MSOS\Php\Scrapers;

use MSOS\Php\Login;
use MSOS\Php\Queries\LoginQuery;
use MSOS\Php\Queries\RegisterQuery;

session_start();

$login = $_POST['login'];
$password = $_POST['password'];
$password_repeat = $_POST['password_repeat'];

$LQ = new LoginQuery();

if ($password != $password_repeat) {
    $_SESSION['errorRegister'] = 'Hasła nie są takie same.';
    header('Location: ./../../Sites/home.Php');
}

if ($LQ->isUserExist($login)) {
    $_SESSION['errorRegister'] = 'Nazwa użytkownika zajęta.';
    header('Location: ./../../Sites/home.Php');
}

$RQ = new RegisterQuery();
$createUser = $RQ->registerUser($login, $password);

if (!is_null($createUser)) {
    $_SESSION['userData'] = new Login($createUser[0], $createUser[1], $createUser[2]);
    $_SESSION['is_logged'] = true;
    header('Location: ./../../index.Php');
} else {
    $_SESSION['errorRegister'] = 'Błąd podczas rejestracji.';
    header('Location: ./../../Sites/home.Php');
}
