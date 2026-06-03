<?php

namespace App\Service;

use App\Repository\ApiKeyRepository;

class ApiKeyService {

    public function __construct(
        protected ApiKeyRepository $repository
    ){}
    public function create(): array
    {
        return [];
    }

    public function list()
    {
        return $this->repository->getAllKeys();
        /*return [
            ['id' => 'key 1']
        ];*/
    }

    public function revoke(): array
    {
        return [];
    }
}