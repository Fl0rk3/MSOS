<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once(__DIR__ . '/config.php');

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../backend/Entity'], // folder with your entities
    isDevMode: true
);

$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => DBHOST,
    'port' => 3306,
    'dbname' => DBNAME,
    'user' => DBUSER,
    'password' => DBPWD,
    'charset' => 'utf8mb4',
]);

// Returns an EntityManagerInterface
return new EntityManager($connection, $config);