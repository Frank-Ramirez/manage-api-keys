<?php

namespace App\Controller;

use App\Service\ApiKeyService;

class ApiKeyController {

    public function __construct(
        private ApiKeyService $apiKeyService,
    )
    {}

    public function index() {
        return $this->apiKeyService->list();
    }
}