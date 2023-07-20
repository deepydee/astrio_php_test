<?php

declare(strict_types=1);

function searchCategoryById(array $categories, ?int $id = null): ?string
{
    if ($id === null || $id < 1) {
        throw new InvalidArgumentException('Invalid ID provided');
    }

    foreach ($categories as $category) {
        if (!is_array($category)) {
            throw new InvalidArgumentException('Invalid category structure');
        }

        if ($category['id'] === $id) {
            return $category['title'];
        }

        if (isset($category['children']) && is_array($category['children'])) {
            $result = searchCategoryById($category['children'], $id);

            if ($result) {
                return $result;
            }
        }
    }

    return null;
}
