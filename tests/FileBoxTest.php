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

    public function testFileBoxSingletonInstanceCantBeCloned(): void
    {
        $instance1 = FileBox::getInstance(filePath: self::$dataFile);
        $instance2 = FileBox::getInstance(filePath: self::$dataFile);

        $this->assertSame($instance1, $instance2, 'Both instances should be the same.');

        try {
            $clonedInstance = clone $instance1;
        } catch (\RuntimeException $e) {
            $this->assertSame('Cloning the FileBox singleton instance is not allowed.', $e->getMessage());
            return;
        }

        $this->fail('Expected exception not thrown when cloning.');
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

    public function testDataWithExistingKeysIsReplacedByNewValues(): void
    {
        $fileBox = FileBox::getInstance(filePath: self::$dataFile);

        $fileBox->setData('name', 'John');
        $fileBox->setData('age', 30);
        $fileBox->setData('email', 'john@example.com');
        $fileBox->save();

        $loadedFileBox = FileBox::getInstance(filePath: self::$dataFile);

        $this->assertEquals('John', $loadedFileBox->getData('name'));
        $this->assertEquals(30, $loadedFileBox->getData('age'));
        $this->assertEquals('john@example.com', $loadedFileBox->getData('email'));

        // Update existing keys with new values
        $loadedFileBox->setData('name', 'Jane');
        $loadedFileBox->setData('age', 32);
        $loadedFileBox->save();

        // Create a new FileBox instance to load the updated data
        $updatedFileBox = FileBox::getInstance(filePath: self::$dataFile);

        // Verify that the data has been updated correctly
        $this->assertEquals('Jane', $updatedFileBox->getData('name'));
        $this->assertEquals(32, $updatedFileBox->getData('age'));
        $this->assertEquals('john@example.com', $updatedFileBox->getData('email'));
    }

    protected function tearDown(): void
    {
        if (file_exists(self::$dataFile)) {
            unlink(self::$dataFile);
        }
    }
}
