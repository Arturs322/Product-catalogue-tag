<?php

namespace App\Validation;

class ProductsValidator
{
    private array $errors;
    public function getErrors(): array
    {
        return $this->errors;
    }
    public function validate(array $data): void
    {
        if (isset($data['title']))
        {
            $this->errors['title'] = 'Title must be written.';
        }

        if (count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }
}