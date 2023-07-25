<?php

declare(strict_types=1);

namespace App\Interfaces;

interface Boxable
{
    public function getData(string $key);
    public function setData(string $key, int|float|string|bool|array $value);
    public function save(): void;
    public function load(): void;
}
