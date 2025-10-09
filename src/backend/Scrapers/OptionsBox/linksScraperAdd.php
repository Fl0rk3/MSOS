<?php

namespace MSOS\backend\Scrapers\OptionsBox;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use MSOS\backend\ctrl\LinksRenderer;
use MSOS\backend\Providers\Links\DoctrineLinksProvider;
use MSOS\backend\Queries\OptionsBox\LinkQuery;

session_start();

/** @var array{id:int, username:string, is_admin:bool}|null $user */
$user = $_SESSION['userData'] ?? null;

if (!$user) {
    echo json_encode(['success' => false, 'html' => '', 'message' => 'User error.']);
    exit;
}

$user_id = $user['id'];
$rawName = $_POST['urlName'] ?? null;
$rawUrl = $_POST['url'] ?? null;

if (!is_string($rawName) || !is_string($rawUrl)) {
    echo json_encode(['success' => false, 'html' => '', 'message' => 'Invalid parameters.']);
    exit;
}

$urlName = trim($rawName);
$url = trim($rawUrl);

if ($urlName === '' || $url === '') {
    echo json_encode(['success' => false, 'html' => '', 'message' => 'Name and URL cannot be empty.']);
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
        echo json_encode(['success' => true, 'html' => $soup, 'message' => 'Link ' . $urlName . ' has been successfully added.']);
    } else {
        echo json_encode(['success' => false, 'html' => '', 'message' => 'Link could not be added. It is probably already in use.']);
    }
} catch (ORMException $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
