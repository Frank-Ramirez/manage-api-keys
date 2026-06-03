<?php

use App\Controller\ApiKeyController;
use App\Service\ApiKeyService;
use App\Repository\ApiKeyRepository;
use App\Database\Connection;
use App\Http\Router;
use App\Logging\LoggerManage;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

try {

  //definir aqui el env es mejor para evitar crear instancias cada vez que se usa la clase Connection
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();

  $logger = new LoggerManage();
  $logger->Logger('info', 'hols');

  $repository = new ApiKeyRepository(Connection::getConnection());
  $service = new ApiKeyService($repository);
  $controller = new ApiKeyController($service);
  $router = new Router([$controller]);

  $response = $router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
  );

  http_response_code($response['status']);
  echo json_encode($response['data'], JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
  $logger->Logger('critical', 'Index error - Exception: ' . $e->getMessage() . "\n StackTrace: " . $e->getTraceAsString());
  http_response_code(500);
  echo json_encode(['error' => 'Internal server error'], JSON_UNESCAPED_UNICODE);
}
//echo print_r($path,true);
