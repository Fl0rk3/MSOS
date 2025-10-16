<?php

declare(strict_types=1);

namespace MSOS\backend\Scraper\OptionsBox;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use MSOS\backend\Query\OptionsBox\SettingQuery;

session_start();

/** @var array{id:int, username:string, is_admin:bool}|null $user */
$user = $_SESSION['userData'] ?? null;

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User error.']);
    exit;
}

foreach ($_POST as $setting_name => $value) {
    if (!is_string($setting_name) || !is_string($value)) {
        echo json_encode(['success' => false, 'message' => 'Invalid parameters.']);
        exit;
    }

    if ($setting_name === '' || $value === '') {
        echo json_encode(['success' => false, 'message' => 'Parameters cannot be empty.']);
    }
}

/** @var EntityManagerInterface $em */
$em = require __DIR__ . '/../../../Config/bootstrap.php';


$setting_query = new SettingQuery($em);

try {
    $result = $setting_query->updateSettings($user['id'], $_POST);
    if ($result === 'true') {
        echo json_encode(['success' => true, 'message' => 'Settings has been successfully changed.']);
    } elseif ($result === 'no_change') {
        echo json_encode(['success' => false, 'message' => 'No changes have been made.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Settings could not be changed.']);
    }
} catch (ORMException $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
