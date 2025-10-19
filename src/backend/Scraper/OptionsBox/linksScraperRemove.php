<?php

namespace MSOS\backend\Scrapers\OptionsBox;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use MSOS\backend\ctrl\LinksRenderer;
use MSOS\backend\Provider\Links\DoctrineLinksProvider;
use MSOS\backend\Query\OptionsBox\LinkQuery;

session_start();

/** @var array{id:int, username:string, is_admin:bool}|null $user */
$user = $_SESSION['userData'] ?? null;

if (!$user) {
    echo json_encode(['success' => false, 'html' => '', 'message' => 'User error.']);
    exit;
}

$user_id = $user['id'];
$urlName = $_POST['urlName'];

if (!is_string($urlName)) {
    echo json_encode(['success' => false, 'html' => '', 'message' => 'URL error.']);
    exit;
}

/** @var EntityManagerInterface $em */
$em = require __DIR__ . '/../../../Config/bootstrap.php';

$linkQuery = new LinkQuery($em);

try {
    $result = $linkQuery->removeLink($user_id, $urlName);
    if ($result) {
        $linksProvider = new DoctrineLinksProvider($em);
        $links = $linksProvider->forUser($user['id']);
        $soup = LinksRenderer::toHtml($links);
        echo json_encode(['success' => true, 'html' => $soup, 'message' => 'Link ' . $urlName . ' has been successfully removed.']);
    } else {
        echo json_encode(['success' => false, 'html' => '', 'message' => 'Link could not be removed.']);
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
