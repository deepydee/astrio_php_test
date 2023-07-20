<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class IsCorrectHTMLStructureTest extends TestCase
{
    protected function setUp(): void
    {
        require_once 'src/isCorrectHTMLStructure.php';
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

    public function testEmptyInputReturnsTrue()
    {
        $emptyTags = [];
        $result = isCorrectHTMLStructure($emptyTags);
        $this->assertTrue($result);
    }

    public function testInvalidTagsReturnsFalse()
    {
        $invalidTags = ['<a>', '<div>', 'invalid', '</a>'];
        $result = isCorrectHTMLStructure($invalidTags);
        $this->assertFalse($result);
    }

    public function testNestedCorrectHTMLStructureReturnsTrue()
    {
        $nestedCorrectTags = ['<a>', '<div>', '<span>', '</span>', '</div>', '</a>'];
        $result = isCorrectHTMLStructure($nestedCorrectTags);
        $this->assertTrue($result);
    }

    public function testMismatchedClosingTagReturnsFalse()
    {
        $mismatchedClosingTag = ['<a>', '<div>', '</span>', '</div>', '</a>'];
        $result = isCorrectHTMLStructure($mismatchedClosingTag);
        $this->assertFalse($result);
    }

    public function testOnlyOpeningTagsReturnsFalse()
    {
        $onlyOpeningTags = ['<a>', '<div>', '<span>'];
        $result = isCorrectHTMLStructure($onlyOpeningTags);
        $this->assertFalse($result);
    }

    public function testOnlyClosingTagsReturnsFalse()
    {
        $onlyClosingTags = ['</a>', '</div>', '</span>'];
        $result = isCorrectHTMLStructure($onlyClosingTags);
        $this->assertFalse($result);
    }
}
