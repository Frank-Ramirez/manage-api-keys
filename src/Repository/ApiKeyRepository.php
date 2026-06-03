<?php

namespace App\Repository;

use PDO;

class ApiKeyRepository
{

  /*
    * @var PDO $conn -> Object type App\Database\Connection
    */

  public function __construct(
    private PDO $conn,
  ) {}

  public function getAllKeys(): array
  {
    $stmt = $this->conn->query(
      "SELECT entity_id, name, is_active, revoked_At, created_At, updated_At
       FROM manage_keys
       ORDER BY entity_id DESC"
    );

    return $stmt->fetchAll();
  }

  public function createKey(string $name, string $hashedKey): int
  {
    $stmt = $this->conn->prepare(
      "INSERT INTO manage_keys (api_key, name, is_active, revoked_At)
       VALUES (:api_key, :name, :is_active, :revoked_At)"
    );

    $stmt->execute([
      'api_key' => $hashedKey,
      'name' => $name,
      'is_active' => 1,
      'revoked_At' => null,
    ]);

    return (int) $this->conn->lastInsertId();
  }

  public function revokeKey(int $id): bool
  {
    $stmt = $this->conn->prepare(
      "UPDATE manage_keys
       SET is_active = :is_active,
           revoked_At = CURRENT_TIMESTAMP
       WHERE entity_id = :id
         AND is_active = 1"
    );

    $stmt->execute([
      'id' => $id,
      'is_active' => 0,
    ]);

    return $stmt->rowCount() > 0;
  }

  public function getActiveKeys(): array
  {
    $stmt = $this->conn->query(
      "SELECT api_key
       FROM manage_keys
       WHERE is_active = 1"
    );

    return $stmt->fetchAll();
  }
}
