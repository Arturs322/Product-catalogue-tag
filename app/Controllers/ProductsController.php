<?php

namespace App\Controllers;

use App\Models\Product;
use App\Repositories\CsvProductsRepository;
use App\Repositories\MySqlRepository;
use App\Repositories\ProductsRepository;
use App\Validation\FormValidationException;
use App\Validation\ProductsValidator;
use App\View;
use Ramsey\Uuid\Uuid;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\FilesystemLoader;

class ProductsController
{
    private ProductsRepository $productsRepository;
    private ProductsValidator $productsValidator;
    private Environment $twig;

    public function __construct()
    {
        $this->productsRepository = new MySqlRepository();
        $this->productsValidator = new ProductsValidator();

        $loader = new FilesystemLoader(base_path() . 'app/Views/');
        $this->twig = new Environment($loader, []);
    }

    public function index(): View
    {
        $products = $this->productsRepository->getAll($_GET);
        return new View('products/index.twig', [
            'products' => $products
        ]);

    }

    public function create()
    {
        require_once "app/Views/create.twig";
    }

    public function store()
    {
        try {
            $this->productsRepository->validate($_POST);
            $product = new Product(
                Uuid::uuid4(),
                $_POST['title'],
                $_POST['last_updated']
            );
            $this->productsRepository->save($product);
            redirect('/products');
        } catch (FormValidationException $exception) {
            $_SESSION['errors'] = $this->productsValidator->getErrors();
            redirect('/products/create');
        }

    }

    public function delete(array $vars)
    {
        $id = $vars['id'] ?? null;
        if ($id == null) header('Location: /');
        $product = $this->productsRepository->getOne($id);

        if ($product !== null) {
            $this->productsRepository->delete($product);
        }

        header('Location /products');
    }

    public function show(array $vars)
    {
        $id = $vars['id'] ?? null;
        if ($id == null) header('Location: /');
        $product = $this->productsRepository->getOne($id);

        if ($product === null) header('Location: /');

        return new View('products/show.twig', [
            'product' => $product
        ]);
    }
}