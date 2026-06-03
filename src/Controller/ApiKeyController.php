<?php

namespace App\Controller;

use App\Service\ApiKeyService;
use App\Config\RouterProviderInterface;

class ApiKeyController implements RouterProviderInterface
{

  public function __construct(
    private ApiKeyService $apiKeyService,
  ) {}

  /*Todos los handlers reciben los mismos parametros:
$vars contiene los parametros extraidos de la ruta por FastRoute
y $body contiene el JSON del request.*/


  public function index(array $vars, array $body): array
  {
    return [
      'status' => 200,
      'data' => $this->apiKeyService->list(),
    ];
  }

  public function create(array $vars, array $body): array
  {
    if (($body['_invalid_json'] ?? false) === true) {
      return [
        'status' => 400,
        'data' => ['error' => 'Invalid JSON body'],
      ];
    }

    if (!isset($body['name']) || !is_string($body['name']) || trim($body['name']) === '') {
      return [
        'status' => 422,
        'data' => ['error' => 'Field "name" is required'],
      ];
    }

    return [
      'status' => 201,
      'data' => $this->apiKeyService->create(trim($body['name'])),
    ];
  }

  public function revoke(array $vars, array $body): array
  {
    $id = (int) ($vars['id'] ?? 0);

    return [
      'status' => 200,
      'data' => $this->apiKeyService->revoke($id),
    ];
  }

  public function validate(array $vars, array $body): array
  {
    if (($body['_invalid_json'] ?? false) === true) {
      return [
        'status' => 400,
        'data' => ['error' => 'Invalid JSON body'],
      ];
    }

    if (!isset($body['key']) || !is_string($body['key']) || trim($body['key']) === '') {
      return [
        'status' => 422,
        'data' => ['error' => 'Field "key" is required'],
      ];
    }

    return [
      'status' => 200,
      'data' => [
        'valid' => $this->apiKeyService->validate(trim($body['key'])),
      ],
    ];
  }

  public function getRoutes(): array
  {
    return [
      [
        'method' => 'POST',
        'path' => '/api/keys',
        'handler' => 'create',
      ],
      [
        'method' => 'GET',
        'path' => '/api/keys',
        'handler' => 'index',
      ],
      [
        'method' => 'POST',
        'path' => '/api/keys/{id:\d+}/revoke',
        'handler' => 'revoke',
      ],
      [
        'method' => 'POST',
        'path' => '/api/keys/validate',
        'handler' => 'validate',
      ],
    ];
  }
}

