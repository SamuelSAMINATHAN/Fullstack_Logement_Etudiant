<?php

class Annonce extends Model
{
    public function getDernieresAnnonces(int $limit = 10): array
    {
        $sql = 'SELECT * FROM annonce ORDER BY date_publication DESC LIMIT :lim';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = 'SELECT * FROM annonce WHERE id_annonce = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row ?: null;
    }
}

