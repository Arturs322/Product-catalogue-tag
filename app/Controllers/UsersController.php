<?php

namespace App\Controllers;

use App\Models\Collections\UsersCollection;
use App\Models\User;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepository;
use App\View;
use Twig\Environment;

class UsersController
{
    private UsersRepository $usersRepository;
    private Environment $twig;

    public function __construct()
    {
        $this->usersRepository = new MysqlUsersRepository();
    }

    public function index(): View
    {
        $users = $this->usersRepository->getAll($_GET);
        echo $this->twig->render("/products/index.twig", [
            'users' => $users
        ]);
    }
}