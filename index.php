<?php

use DI\Container;
use App\Middlewares\AuthorizedMiddleware;
use App\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use DI\ContainerBuilder;

require_once "app/Views/index.twig";
require_once 'vendor/autoload.php';

session_start();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'TasksController@index');
    $r->addRoute('GET', '/products', 'TasksController@index');
    $r->addRoute('GET', '/products/create', 'TasksController@create');
    $r->addRoute('POST', '/products', 'TasksController@store');
    $r->addRoute('POST', '/products/{id}', 'TasksController@delete');
    $r->addRoute('GET', '/products/{id}', 'TasksController@show');

    $r->addRoute('GET', '/users', 'UsersController@index');

    $r->addRoute('GET', '/register', 'AuthController@showRegisterForm');
    $r->addRoute('POST', '/register', 'AuthController@register');

    $r->addRoute('GET', '/login', 'AuthController@showLoginForm');
    $r->addRoute('POST', '/login', 'AuthController@login');

    $r->addRoute('POST', '/logout', 'AuthController@logout');
});

function base_path(): string
{
    return __DIR__;
}

function redirect(string $url)
{
    header("Location: $url");
    exit();
}

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
$loader = new FilesystemLoader(base_path() . 'app/Views/');
$templateEngine = new Environment($loader, []);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $middlewares = [
            'ProductsController@show' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@create' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@users' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@login' => [
                AuthorizedMiddleware::class
            ],
            'ProductsController@logout' => [
                AuthorizedMiddleware::class
            ],
        ];
        if (array_key_exists($handler, $middlewares))
        {
            foreach ($middlewares[$handler] as $middleware)
            {
                (new $middleware)->handle();
            }
        }
        
        $builder = new ContainerBuilder();
        $container = $builder->build();
        [$controller, $method] = explode('@', $handler);
        $controller = 'App\Controllers\\' . $controller;
        $controller = new $controller;
        $response = $controller->$method($vars);

        if ($response instanceof View) {
            echo $templateEngine->render(
                $response->getTemplate(),
                $response->getArgs(),
            );
        }
        if ($response instanceof View) {
            echo $templateEngine->render(
                $response->getTemplate(),
                $response->getArgs(),
            );
        }
        break;
}

unset($_SESSION['errors']);
