<?php

namespace App\Repositories;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;
use PDO;
use PDOException;

class MySqlTagsRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $host = '127.0.0.1';
        $db = 'Product-Catalogue';
        $user = 'root';
        $pass = '';
        $dsn = "mysql:host = $host;dbname=$db;charset=UTF8";
        try {
            $this->connection = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    public function getAll(): TagsCollection
    {
        $tagsCollection = new TagsCollection();
        $sql = "SELECT * FROM tag";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();


        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($records as $record)
        {
            $tagsCollection->add(new Tag($record['name']));
        }
        return $tagsCollection;
    }
}

