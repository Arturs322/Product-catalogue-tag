<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use PDO;
use PDOException;

class MySqlRepository implements ProductsRepository
{

    private PDO $connection;

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

    public function getAll(array $filters = []): ProductsCollection
    {
        $sql = "SELECT * FROM products";
        $params = [];
        if (isset($filters['status'])) {
            $sql .= " WHERE status = ?";
            $params[] = $filters['status'];
        }
        $sql .= "ORDER BY created_at DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$params]);

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $collection = new ProductsCollection();

        foreach ($products as $product) {
            $collection->add(new Product(
                $product['id'],
                $product['title'],
                $product['status'],
                $product['created_at'],
            ));
        }
        return $collection;
    }

    public function getOne(string $id): ?Product
    {
        $sql = "SELECT * FROM  products WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        $task = $stmt->fetch();

        return new Product(
            $task['name'],
            $task['category'],
            $task['lastUpdated'],
            $task['quantity'],
            $task['created_at'],
        );
    }

    public function save(Product $product): void
    {
        $sql = "INSERT INTO products (name, category, lastUpdated, quantity, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            $product->getName(),
            $product->getCategory(),
            $product->getLastUpdated(),
            $product->getQuantity(),
            $product->getCreatedAt()
        ]);
    }

    public function delete(Product $product): void
    {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$product->getName()]);
    }
}