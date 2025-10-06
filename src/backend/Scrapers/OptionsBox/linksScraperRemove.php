<?php

namespace MSOS\backend\Scrapers\OptionsBox;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use MSOS\backend\ctrl\LinksRenderer;
use MSOS\backend\Providers\Links\DoctrineLinksProvider;
use MSOS\backend\Queries\OptionsBox\LinkQuery;

session_start();

/** @var array{id:int, username:string, is_admin:bool}|null $user */
$user = $_SESSION['userData'] ?? null;

if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'html' => '']);
    exit;
}

$user_id = $user['id'];
$urlName = $_POST['urlName'];

if (!is_string($urlName)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'html' => '']);
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
        echo json_encode(['success' => true, 'html' => $soup]);
    } else {
        echo json_encode(['success' => false, 'html' => '']);
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
