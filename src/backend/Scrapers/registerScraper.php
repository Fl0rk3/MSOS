<?php

namespace MSOS\backend\Scrapers;

use Doctrine\ORM\EntityManagerInterface;
use MSOS\backend\Queries\LoginQuery;
use MSOS\backend\Queries\RegisterQuery;

session_start();

$login = $_POST['login'];
$password = $_POST['password'];
$password_repeat = $_POST['password_repeat'];

if (!is_string($login) || !is_string($password) || !is_string($password_repeat)) {
    $_SESSION['errorLogin'] = "Data error.";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}

if ($login === '' || mb_strlen($login) > 40) {
    $_SESSION['errorLogin'] = "Missing or too long username [max 40 characters].";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}

if ($password === '' || mb_strlen($login) > 32) {
    $_SESSION['errorLogin'] = "Missing password [max 32 characters].";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}

if ($password_repeat === '') {
    $_SESSION['errorLogin'] = "Missing repeat password.";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}


if ($password != $password_repeat) {
    $_SESSION['errorRegister'] = 'Passwords are not the same.';
    header('Location: ./../../Sites/home.Php');
}

/** @var EntityManagerInterface $em */
$em = require __DIR__ . '/../../Config/bootstrap.php';

$LQ = new LoginQuery($em);

if ($LQ->isUserExist($login)) {
    $_SESSION['errorRegister'] = 'Username is already taken.';
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
    $_SESSION['errorRegister'] = 'Error during register process.';
    header('Location: ./../../../public/Sites/home.php');
}
