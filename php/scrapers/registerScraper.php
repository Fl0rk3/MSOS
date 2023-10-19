<?php
session_start();

$login = $_POST['login'];
$password = $_POST['password'];
$password_repeat = $_POST['password_repeat'];

include('./../queries/LoginQuery.php');
include('./../queries/RegisterQuery.php');
include('./../Login.php');

$LQ = new LoginQuery();

if ($password != $password_repeat) {
    $_SESSION['errorRegister'] = 'Hasła nie są takie same.';
    header('Location: ./../../sites/home.php');
}

if ($LQ->isUserExist($login)) {
    $_SESSION['errorRegister'] = 'Nazwa użytkownika zajęta.';
    header('Location: ./../../sites/home.php');
}

$RQ = new RegisterQuery();
$createUser = $RQ->registerUser($login, $password);

if (!is_null($createUser)) {
    $_SESSION['userData'] = new Login($createUser[0], $createUser[1], $createUser[2]);
    $_SESSION['is_logged'] = true;
    header('Location: ./../../index.php');
} else {
    $_SESSION['errorRegister'] = 'Błąd podczas rejestracji.';
    header('Location: ./../../sites/home.php');
}
