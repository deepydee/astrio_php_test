<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class IsCorrectHTMLStructureTest extends TestCase
{
    protected function setUp(): void
    {
        require_once 'src/functions/isCorrectHTMLStructure.php';
    }

    public function testCorrect_HTML_StructureReturnsTrue(): void
    {
        $correctTags = ['<a>', '<div>', '</div>', '</a>', '<span>', '</span>'];
        $result = isCorrectHTMLStructure($correctTags);
        $this->assertTrue($result);
    }

    public function testIncorrect_HTML_StructureReturnsFalse(): void
    {
        $incorrectTags = ['<a>', '<div>', '</a>'];
        $result = isCorrectHTMLStructure($incorrectTags);
        $this->assertFalse($result);
    }

    public function testEmptyInputReturnsTrue(): void
    {
        $emptyTags = [];
        $result = isCorrectHTMLStructure($emptyTags);
        $this->assertTrue($result);
    }

    public function testInvalidTagsReturnsFalse(): void
    {
        $invalidTags = ['<a>', '<div>', 'invalid', '</a>'];
        $result = isCorrectHTMLStructure($invalidTags);
        $this->assertFalse($result);
    }

    public function testNestedCorrectHTMLStructureReturnsTrue(): void
    {
        $nestedCorrectTags = ['<a>', '<div>', '<span>', '</span>', '</div>', '</a>'];
        $result = isCorrectHTMLStructure($nestedCorrectTags);
        $this->assertTrue($result);
    }

    public function testMismatchedClosingTagReturnsFalse(): void
    {
        $mismatchedClosingTag = ['<a>', '<div>', '</span>', '</div>', '</a>'];
        $result = isCorrectHTMLStructure($mismatchedClosingTag);
        $this->assertFalse($result);
    }

    public function testOnlyOpeningTagsReturnsFalse(): void
    {
        $onlyOpeningTags = ['<a>', '<div>', '<span>'];
        $result = isCorrectHTMLStructure($onlyOpeningTags);
        $this->assertFalse($result);
    }

    public function testOnlyClosingTagsReturnsFalse(): void
    {
        $onlyClosingTags = ['</a>', '</div>', '</span>'];
        $result = isCorrectHTMLStructure($onlyClosingTags);
        $this->assertFalse($result);
    }
}
