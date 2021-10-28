<?php

class Request
{
    private array $post;
    private array $get;
    private array $vars;

    public function __construct(array $post, array $get, array $vars)
    {
        $this->post = $post;
        $this->get = $get;
        $this->vars = $vars;
    }

    public function get(string $key): ?string
    {
        return $value = $this->get[$key] ?? null;

    }

    public function post(string $key): ?string
    {
        return $value = $this->post[$key] ?? null;
    }

    public function vars(string $key): ?string
    {
        return $value = $this->vars[$key] ?? null;
    }
}