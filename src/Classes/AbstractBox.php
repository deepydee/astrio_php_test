<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\Boxable;

abstract class AbstractBox implements Boxable
{
    protected $data = [];

    public function getData(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function setData(string $key, int|float|string|bool|array $value)
    {
        $this->data[$key] = $value;
    }
}
