<?php

namespace MSOS\backend\Scrapers\OptionsBox;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use MSOS\backend\ctrl\LinksRenderer;
use MSOS\backend\Provider\Links\DoctrineLinksProvider;
use MSOS\backend\Query\OptionsBox\LinkQuery;

session_start();

/** @var array{id:int, username:string, is_admin:bool}|null $user */
$user = $_SESSION['userData'] ?? null;

if (!$user) {
    echo json_encode(['success' => false, 'html' => '', 'message' => 'alert.error.user_error']);
    exit;
}

$user_id = $user['id'];
$rawName = $_POST['urlName'] ?? null;
$rawUrl = $_POST['url'] ?? null;

if (!is_string($rawName) || !is_string($rawUrl)) {
    echo json_encode(['success' => false, 'html' => '', 'message' => 'alert.error.invalid_parameters']);
    exit;
}

$urlName = trim($rawName);
$url = trim($rawUrl);

if ($urlName === '' || $url === '') {
    echo json_encode(['success' => false, 'html' => '', 'message' => 'alert.error.empty_parameters']);
    exit;
}

/** @var EntityManagerInterface $em */
$em = require __DIR__ . '/../../../Config/bootstrap.php';
$linkQuery = new LinkQuery($em);

try {
    $result = $linkQuery->addLink($user_id, $urlName, $url);
    if ($result) {
        $linksProvider = new DoctrineLinksProvider($em);
        $links = $linksProvider->forUser($user['id']);
        $soup = LinksRenderer::toHtml($links);
        echo json_encode(['success' => true, 'html' => $soup, 'message_key' => 'alert.success.url_added', 'message_vars' => ['url_name' => $urlName]]);
    } else {
        echo json_encode(['success' => false, 'html' => '', 'message' => 'alert.error.url_used']);
    }
} catch (ORMException $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
