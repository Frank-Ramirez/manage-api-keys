<?php

namespace App\Service;

use App\Repository\ApiKeyRepository;

class ApiKeyService
{

    public function __construct(
        protected ApiKeyRepository $repository
    ) {}

    public function create(string $name): array
    {
        $plainKey = 'ak_' . bin2hex(random_bytes(16));
        $hashedKey = password_hash($plainKey, PASSWORD_DEFAULT);
        $id = $this->repository->createKey($name, $hashedKey);

        return [
            'id' => $id,
            'key' => $plainKey,
            'name' => $name,
        ];
    }

    public function list(): array
    {
        return $this->repository->getAllKeys();
    }

    public function revoke(int $id): array
    {
        return [
            'revoked' => $this->repository->revokeKey($id),
        ];
    }

    public function validate(string $key): bool
    {
        $rows = $this->repository->getActiveKeys();

        foreach ($rows as $row) {
            if (isset($row['api_key']) && password_verify($key, $row['api_key'])) {
                return true;
            }
        }

        return false;
    }
}
