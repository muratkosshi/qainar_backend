<?php

declare(strict_types=1);

class Product
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName(int $value)
    {
        return $value;
    }

    // Конструктор копирования
    public function __clone()
    {
        // Не изменяем имя при копировании
    }
}



$product = new Product();

$product->getName();
