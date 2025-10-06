<?php

namespace MSOS\Php\Scrapers\OptionsBox;

use Exception;
use MSOS\Php\OptionsBox\Links;
use MSOS\Php\Queries\OptionsBox\LinkQuery;

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
