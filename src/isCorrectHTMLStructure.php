<?php

declare(strict_types=1);

function isCorrectHTMLStructure(array $tags): bool
{
    $stack = [];

    foreach ($tags as $tag) {
        if (strpos($tag, '</') === 0) { // Closing tag found
            $openingTag = '<' . substr($tag, 2);

            if (end($stack) === $openingTag) {
                array_pop($stack); // Remove matching opening tag from the stack
            } else {
                return false; // Mismatched closing tag
            }
        } elseif (strpos($tag, '<') === 0) {  // Opening tag found
            $stack[] = $tag;
        } else {
            return false; // Invalid tag format
        }
    }

    return empty($stack);
}
