<?php

declare(strict_types=1);

namespace App;

use App\Classes\FileBox;
use PHPUnit\Framework\TestCase;

class FileBoxTest extends TestCase
{
    private static $dataFile = 'data.txt';

    protected function setUp(): void
    {
        if (file_exists(self::$dataFile)) {
            unlink(self::$dataFile);
        }
    }

    public function testGetInstance(): void
    {
        $fileBox1 = FileBox::getInstance(filePath: self::$dataFile);
        $fileBox2 = FileBox::getInstance(filePath: self::$dataFile);

        $this->assertInstanceOf(FileBox::class, $fileBox1);
        $this->assertInstanceOf(FileBox::class, $fileBox2);
        $this->assertSame($fileBox1, $fileBox2);
    }

    public function testSaveAndLoadData(): void
    {
        $fileBox = FileBox::getInstance(filePath: self::$dataFile);
        $fileBox->setData('name', 'John');
        $fileBox->setData('age', 30);
        $fileBox->save();

        $newFileBox = FileBox::getInstance(filePath: self::$dataFile);
        $this->assertEquals('John', $newFileBox->getData('name'));
        $this->assertEquals(30, $newFileBox->getData('age'));
    }

    public function testGetDataWithNonExistingKey(): void
    {
        $fileBox = FileBox::getInstance(filePath: self::$dataFile);
        $this->assertNull($fileBox->getData('non_existing_key'));
    }

    protected function tearDown(): void
    {
        if (file_exists(self::$dataFile)) {
            unlink(self::$dataFile);
        }
    }
}
