<?php

declare(strict_types=1);

namespace App;

use App\Classes\DBBox;
use PHPUnit\Framework\TestCase;

class DBBoxTest extends TestCase
{
    private static \PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new \PDO('sqlite::memory:');
        self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $createTableQuery = "CREATE TABLE IF NOT EXISTS test_table (
            `key` VARCHAR(255) PRIMARY KEY,
            `value` TEXT
        )";
        self::$pdo->exec($createTableQuery);
    }

    public function testGetInstance(): void
    {
        $tableName = 'test_table';
        $dbBox1 = DBBox::getInstance(self::$pdo, $tableName);
        $dbBox2 = DBBox::getInstance(self::$pdo, $tableName);

        $this->assertInstanceOf(DBBox::class, $dbBox1);
        $this->assertInstanceOf(DBBox::class, $dbBox2);
        $this->assertSame($dbBox1, $dbBox2);
    }

    public function testFileBoxSingletonInstanceCantBeCloned(): void
    {
        $tableName = 'test_table';

        $instance1 = DBBox::getInstance(self::$pdo, $tableName);
        $instance2 = DBBox::getInstance(self::$pdo, $tableName);

        $this->assertSame($instance1, $instance2, 'Both instances should be the same.');

        try {
            $clonedInstance = clone $instance1;
        } catch (\RuntimeException $e) {
            $this->assertSame('Cloning the DBBox singleton instance is not allowed.', $e->getMessage());
            return;
        }

        $this->fail('Expected exception not thrown when cloning.');
    }

    public function testSaveAndLoadData(): void
    {
        $tableName = 'test_table';
        $dbBox = DBBox::getInstance(self::$pdo, $tableName);

        $dbBox->setData('name', 'John');
        $dbBox->setData('age', 30);
        $dbBox->save();

        $newDBBox = DBBox::getInstance(self::$pdo, $tableName);
        $this->assertEquals('John', $newDBBox->getData('name'));
        $this->assertEquals(30, $newDBBox->getData('age'));
    }

    public function testGetDataWithNonExistingKey(): void
    {
        $tableName = 'test_table';
        $dbBox = DBBox::getInstance(self::$pdo, $tableName);

        $this->assertNull($dbBox->getData('non_existing_key'));
    }

    public function testDataWithExistingKeysIsReplacedByNewValues(): void
    {
        $tableName = 'test_table';

        $dbBox = DBBox::getInstance(self::$pdo, $tableName);

        $dbBox->setData('name', 'John');
        $dbBox->setData('age', 30);
        $dbBox->setData('email', 'john@example.com');
        $dbBox->save();

        $loadedFileBox = DBBox::getInstance(self::$pdo, $tableName);

        $this->assertEquals('John', $loadedFileBox->getData('name'));
        $this->assertEquals(30, $loadedFileBox->getData('age'));
        $this->assertEquals('john@example.com', $loadedFileBox->getData('email'));

        // Update existing keys with new values
        $loadedFileBox->setData('name', 'Jane');
        $loadedFileBox->setData('age', 32);
        $loadedFileBox->save();

        // Create a new FileBox instance to load the updated data
        $updatedFileBox = DBBox::getInstance(self::$pdo, $tableName);

        // Verify that the data has been updated correctly
        $this->assertEquals('Jane', $updatedFileBox->getData('name'));
        $this->assertEquals(32, $updatedFileBox->getData('age'));
        $this->assertEquals('john@example.com', $updatedFileBox->getData('email'));
    }

    // public function testSaveMethodWithTransactions(): void
    // {
    //     $tableName = 'test_table';

    //     /** @var \PDO&\PHPUnit\Framework\MockObject\MockObject $pdoMock */
    //     $pdoMock = $this->getMockBuilder(\PDO::class)
    //         ->disableOriginalConstructor()
    //         ->getMock();

    //     $pdoMock->expects($this->once())
    //         ->method('beginTransaction');

    //     $pdoMock->expects($this->once())
    //         ->method('commit');

    //     $dbBox = DBBox::getInstance($pdoMock, $tableName);
    //     $dbBox->setData('name', 'John');
    //     $dbBox->setData('age', 30);

    //     $dbBox->save();
    // }

    public static function tearDownAfterClass(): void
    {
        self::$pdo = null;
    }
}
