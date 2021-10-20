<?php

namespace App;

class View
{
    private string $template;
    private array $args;

    public function __construct(string $template, array $args)
    {
        $this->template = $template;
        $this->args = $args;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

}