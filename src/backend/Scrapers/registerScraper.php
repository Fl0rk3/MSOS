<?php

namespace MSOS\backend\Scrapers;

use Doctrine\ORM\EntityManagerInterface;
use MSOS\backend\Queries\LoginQuery;
use MSOS\backend\Queries\RegisterQuery;

session_start();

$login = $_POST['login'];
$password = $_POST['password'];
$password_repeat = $_POST['password_repeat'];


if ($password != $password_repeat) {
    $_SESSION['errorRegister'] = 'Hasła nie są takie same.';
    header('Location: ./../../Sites/home.Php');
}

/** @var EntityManagerInterface $em */
$em = require __DIR__ . '/../../Config/bootstrap.php';

$LQ = new LoginQuery($em);

if ($LQ->isUserExist($login)) {
    $_SESSION['errorRegister'] = 'Nazwa użytkownika zajęta.';
    header('Location: ./../../Sites/home.Php');
}

$RQ = new RegisterQuery($em);
$createUser = $RQ->registerUser($login, $password);

if (!is_null($createUser)) {
    $_SESSION['userData'] = [
        'id' => $createUser['user_id'],
        'username' => $createUser['username'],
        'is_admin' => $createUser['is_admin']
    ];
    $_SESSION['is_logged'] = true;
    header('Location: ./../../../public/index.php');
} else {
    $_SESSION['errorRegister'] = 'Błąd podczas rejestracji.';
    header('Location: ./../../../public/Sites/home.php');
}
