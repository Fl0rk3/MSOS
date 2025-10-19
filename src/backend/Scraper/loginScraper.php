<?php

namespace MSOS\backend\Scrapers;

use Doctrine\ORM\EntityManagerInterface;
use MSOS\backend\Query\LoginQuery;

session_start();

$login = $_POST['login'];
$password = $_POST['password'];

if (!is_string($login) || !is_string($password)) {
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
    $_SESSION['errorLogin'] = "Missing or too long password [max 32 characters].";
    header('Location: ./../../../public/Sites/home.php');
    exit;
}

/** @var EntityManagerInterface $em */
$em = require __DIR__ . '/../../Config/bootstrap.php';

$loginQuery = new LoginQuery($em);

/** @var array{user_id:int, username:string, is_admin:bool}|null $userData */
$userData = $loginQuery->loginUser($login, $password);

if ($userData !== null) {
    $_SESSION['userData'] = [
        'id' => $userData['user_id'],
        'username' => $userData['username'],
        'is_admin' => $userData['is_admin']
    ];
    $_SESSION['is_logged'] = true;
    header('Location: ./../../../public/index.php');
    exit;
}

$_SESSION['errorLogin'] = "Błędne dane logowania.";
header('Location: ./../../../public/Sites/home.php');
exit;

