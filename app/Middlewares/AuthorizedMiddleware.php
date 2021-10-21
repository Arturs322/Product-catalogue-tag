<?php

namespace App\Middlewares;
use App\Auth;
use Middleware;

class AuthorizedMiddleware implements Middleware
{
    public function handle(): void
    {
        if (! Auth::loggedIn())
        {
            header('Location: /login');
            exit;
        }
    }

}