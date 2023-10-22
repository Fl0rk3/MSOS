<?php
include(dirname(__DIR__, 2) . '\Login.php');

session_start();

$user_id = $_SESSION['userData']->getUserID();
$urlName = $_POST['urlName'];
$url = $_POST['url'];

include(dirname(__DIR__, 2) . '\queries\optionsBox\LinkQuery.php');

$linkQuery = new LinkQuery();

try {
    $result = $linkQuery->addLink($user_id, $urlName, $url);
    echo $result;
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
