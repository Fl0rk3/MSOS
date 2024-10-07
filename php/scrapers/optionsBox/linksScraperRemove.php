<?php
include_once(dirname(__DIR__, 2) . '\Login.php');
include_once(dirname(__DIR__, 2) . '\queries\optionsBox\LinkQuery.php');
include_once(dirname(__DIR__, 2) . '\optionsBox\Links.php');

session_start();

$user_id = $_SESSION['userData']->getUserID();
$urlName = $_POST['urlName'];

$linkQuery = new LinkQuery();
$links = new Links();

try {
    $result = $linkQuery->removeLink($user_id, $urlName);
    $_SESSION['userData']->reloadLinks();
    $new_links = $links->getLinksToHTML($_SESSION['userData']->getUserLinks());

    echo json_encode([$result, $new_links]);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
