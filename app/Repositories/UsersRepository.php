<?php

namespace App\Repositories;

use App\Models\Collections\UsersCollection;
use App\Models\User;

interface UsersRepository
{
    public function getAll(): UsersCollection;
    public function getByEmail(string $email): ?User;
    public function getById(string $id): ?User;
    public function save(User $user): void;
}