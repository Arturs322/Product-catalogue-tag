<?php

namespace App;

use App\Models\User;
use App\Repositories\MysqlUsersRepository;

class Auth
{
    public static function loggedIn(): bool
    {
        return isset($_SESSION['authId']);
    }

    public static function user(): ?User
    {
        if (!self::loggedIn()) return null;
        $userRepository = new MysqlUsersRepository();
        return $userRepository->getById($_SESSION['authId']);
    }
}