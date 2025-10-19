<?php

namespace MSOS\backend\Scrapers;

use Doctrine\ORM\EntityManagerInterface;
use MSOS\backend\Query\LoginQuery;
use MSOS\backend\Query\RegisterQuery;

session_start();

$login = $_POST['login'];
$password = $_POST['password'];
$password_repeat = $_POST['password_repeat'];

if (!is_string($login) || !is_string($password) || !is_string($password_repeat)) {
    $_SESSION['errorRegister'] = "Data error.";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}

if ($login === '' || mb_strlen($login) > 40 || mb_strlen($login) < 5) {
    $_SESSION['errorRegister'] = "Missing or incorrect username length [5-40 characters].";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}

if ($password === '' || mb_strlen($login) > 32 || mb_strlen($password) < 8) {
    $_SESSION['errorRegister'] = "Missing or incorrect password length [8-32 characters].";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}

if ($password_repeat === '') {
    $_SESSION['errorRegister'] = "Missing repeat password.";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}


if ($password !== $password_repeat) {
    $_SESSION['errorRegister'] = 'Passwords are not the same.';
    header('Location: ./../../../public/Sites/home.php');
    exit;
}

/** @var EntityManagerInterface $em */
$em = require __DIR__ . '/../../Config/bootstrap.php';

$LQ = new LoginQuery($em);

if ($LQ->isUserExist($login)) {
    $_SESSION['errorRegister'] = 'Username is already taken.';
    header('Location: ./../../../public/Sites/home.php');
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
