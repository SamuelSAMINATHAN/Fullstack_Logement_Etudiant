<?php

class Model extends Database
{
    // ----------------------------------------------------------
    //  findAll(string $table): array
    //  Récupère toutes les lignes d'une table autorisée
    // ----------------------------------------------------------
    protected function findAll(string $table): array
    {
        return $this->selectAll($table);
    }

    // ----------------------------------------------------------
    //  findById(string $table, string $idField, int $id): ?array
    //  Récupère une ligne par son ID
    // ----------------------------------------------------------
    protected function findById(string $table, string $idField, int $id): ?array
    {
        $sql = "SELECT * FROM `$table` WHERE `$idField` = ?";
        return $this->selectOne($sql, [$id]);
    }

    // ----------------------------------------------------------
    //  findWhere(string $table, string $field, mixed $value): array
    //  Récupère plusieurs lignes avec condition simple
    // ----------------------------------------------------------
    protected function findWhere(string $table, string $field, $value): array
    {
        $sql = "SELECT * FROM `$table` WHERE `$field` = ?";
        return $this->select($sql, [$value]);
    }

    // ----------------------------------------------------------
    //  create(string $table, array $data): int
    //  Insère une ligne dynamiquement
    // ----------------------------------------------------------
    protected function create(string $table, array $data): int
    {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');

        $sql = "INSERT INTO `$table` (" . implode(',', $fields) . ")
                VALUES (" . implode(',', $placeholders) . ")";

        $this->insert($sql, array_values($data));
        return $this->lastInsertId();
    }

    // ----------------------------------------------------------
    //  updateById(string $table, string $idField, int $id, array $data): int
    //  Met à jour une ligne par ID
    // ----------------------------------------------------------
    protected function updateById(string $table, string $idField, int $id, array $data): int
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
        }

        $sql = "UPDATE `$table` SET " . implode(', ', $fields) . " WHERE `$idField` = ?";

        $params = array_values($data);
        $params[] = $id;

        return $this->update($sql, $params);
    }

    // ----------------------------------------------------------
    //  deleteById(string $table, string $idField, int $id): int
    //  Supprime une ligne par ID
    // ----------------------------------------------------------
    protected function deleteById(string $table, string $idField, int $id): int
    {
        $sql = "DELETE FROM `$table` WHERE `$idField` = ?";
        return $this->delete($sql, [$id]);
    }
}