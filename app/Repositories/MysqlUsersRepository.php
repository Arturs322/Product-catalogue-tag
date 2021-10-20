<?php

namespace App\Repositories;

use App\Models\Collections\UsersCollection;
use App\Models\User;
use PDO;
use PDOException;

class MysqlUsersRepository implements UsersRepository
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

    public function getAll(): UsersCollection
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([]);

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $collection = new UsersCollection();

        foreach ($users as $user) {
            $collection->add(new User(
                $user['id'],
                $user['name'],
            ));
        }
        return $collection;
    }
    public function getByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($user)) return null;
        return new User(
            $user['id'],
            $user['email'],
            $user['name'],
            $user['password'],
            $user['created_at'],
        );
    }

    public function save(User $user): void
    {
        $sql = "INSERT INTO users (id, email, name, password, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            $user->getId(),
            $user->getEmail(),
            $user->getName(),
            $user->getPassword(),
            $user->getCreatedAt()
        ]);
    }

    public function getById(string $id): ?User
    {
        // TODO: Implement getById() method.
    }
}