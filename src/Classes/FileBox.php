<?php

declare(strict_types=1);

namespace App\Classes;

class FileBox extends AbstractBox
{
    private static ?FileBox $instance = null;

    private function __construct(private string $filePath = 'data.txt')
    {
        $this->filePath = __DIR__ . "/$filePath";
        $this->load();
    }

    public static function getInstance(string $filePath): FileBox
    {
        if (!self::$instance) {
            self::$instance = new self($filePath);
        }

        return self::$instance;
    }

    public function __clone()
    {
        throw new \RuntimeException('Cloning the FileBox singleton instance is not allowed.');
    }


    public function save(): void
    {
        file_put_contents($this->filePath, serialize($this->data));
    }

    public function load(): void
    {
        if (file_exists($this->filePath)) {
            $this->data = unserialize(file_get_contents($this->filePath));
        }
    }
}
