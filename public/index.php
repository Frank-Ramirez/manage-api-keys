<?php

use App\Controller\ApiKeyController;
use App\Service\ApiKeyService;
use App\Repository\ApiKeyRepository;
use App\Database\Connection;

require __DIR__ . '/../vendor/autoload.php';

$repository = new ApiKeyRepository(Connection::getConnection());
$service = new ApiKeyService($repository );
$controller = new ApiKeyController($service);

$path = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
//$method = $_SERVER['REQUEST_METHOD'];

if ( $path == '/api/key') {
    echo json_encode($controller->index());
    exit;    
}

http_response_code(404);
echo json_encode(['path not found']);
//echo print_r($path,true);
