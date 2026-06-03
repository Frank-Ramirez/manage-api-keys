<?php

namespace App\Repository;

use PDO;

class ApiKeyRepository {

    /*
    * @var PDO $conn -> Object type App\Database\Connection
    */

    public function __construct(
        private PDO $conn,
    ) {}

    public function getAllKeys() {
        $rows = $this->conn->query("SELECT entity_id, name, api_key FROM manage_keys");
        return $rows;
    }

    public function createKey() {
        $conn = $this->conn->query("");
    }

    public function removeKey() {}

}