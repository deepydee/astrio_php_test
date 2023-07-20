<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SearchCategoryByIdTest extends TestCase
{
    private array $categories;
    private array $invalidCategories;

    protected function setUp(): void
    {
        require_once 'src/searchCategoryById.php';

        $this->categories = [
            [
                'id' => 1,
                'title' =>  'Обувь',
                'children' => [
                    [
                        'id' => 2,
                        'title' => 'Ботинки',
                        'children' => [
                            ['id' => 3, 'title' => 'Кожа'],
                            ['id' => 4, 'title' => 'Текстиль'],
                        ],
                    ],
                    ['id' => 5, 'title' => 'Кроссовки',],
                ]
            ],
            [
                'id' => 6,
                'title' =>  'Спорт',
                'children' => [
                    [
                        'id' => 7,
                        'title' => 'Мячи'
                    ]
                ]
            ],
        ];

        $this->invalidCategories =  [
            'id' => 6,
            'title' =>  'Спорт',
            'children' => 'string',
        ];
    }

    public function testSearchCategoryFindsTitleById(): void
    {
        $result = searchCategoryById(categories: $this->categories, id: 7);
        $this->assertEquals('Мячи', $result);
    }

    public function testSearchCategoryThrowsAnExceptionOnNegativeId(): void
    {
        try {
            searchCategoryById(categories: $this->categories, id: -1);
        } catch (\Exception $e) {
            $this->assertEquals('Invalid ID provided', $e->getMessage());
            return;
        }

        $this->fail('Invalid ID exception expected');
    }

    public function testSearchCategoryThrowsAnExceptionOnInvalidCategoryStructure(): void
    {
        try {
            searchCategoryById(categories: $this->invalidCategories, id: 12);
        } catch (\Exception $e) {
            $this->assertEquals('Invalid category structure', $e->getMessage());
            return;
        }

        $this->fail('Invalid category structure exception expected');
    }

    public function testSearchCategoryReturnsNullForNotFoundId(): void
    {
        $result = searchCategoryById(categories: $this->categories, id: 100);
        $this->assertNull($result);
    }

    public function testSearchCategoryReturnsNullForEmptyArray(): void
    {
        $result = searchCategoryById([], 3);
        $this->assertNull($result);
    }
}
