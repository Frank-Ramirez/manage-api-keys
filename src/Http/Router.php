<?php

namespace App\Http;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use FastRoute\Dispatcher;

class Router
{

  public function __construct(
    private array $controllers
  ) {}

  public function dispatch(string $method, string $url)
  {
    $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
      foreach ($this->controllers as $controller) {

        foreach ($controller->getRoutes() as $routes) {
          $collector->addRoute(
            $routes['method'],
            $routes['path'],
            [$controller, $routes['handler']]
          );
        }
      }
    });

    $path = parse_url($url, PHP_URL_PATH) ?? '/';
    $path = rawurldecode($path);

    $routeInfo = $dispatcher->dispatch($method, $path);
    // echo print_r($routeInfo, true);

    return match ($routeInfo[0]) {
      Dispatcher::NOT_FOUND => [
        'status' => 404,
        'data' => ['error' => 'Route not found'],
      ],
      Dispatcher::METHOD_NOT_ALLOWED => [
        'status' => 405,
        'data' => [
          'error' => 'Method not allowed',
          'allowed_methods' => $routeInfo[1],
        ],
      ],
      Dispatcher::FOUND => $this->handleFound($routeInfo[1], $routeInfo[2]),
    };
  }

  private function handleFound(callable $handler, array $vars)
  {
    $body = $this->getJsonBody();

    return $handler($vars, $body);
  }

  private function getJsonBody()
  {
    $rawBody = file_get_contents('php://input');

    if ($rawBody === false || $rawBody === '') {
      return [];
    }

    $decoded = json_decode($rawBody, true);

    if (!is_array($decoded)) {
      return [
        '_invalid_json' => true,
      ];
    }

    return $decoded;
  }
}
