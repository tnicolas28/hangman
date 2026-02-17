<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Service;

use App\Infrastructure\Service\WordProvider;
use PHPUnit\Framework\TestCase;

class WordProviderTest extends TestCase
{
    private string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = \tempnam(\sys_get_temp_dir(), 'dict');
        \file_put_contents($this->tempFile, "symfony\nphp\ntwig\n");
    }

    protected function tearDown(): void
    {
        \unlink($this->tempFile);
    }

    public function testGetRandomWordReturnsWordFromFile(): void
    {
        $dictionary = new WordProvider($this->tempFile);
        $word = $dictionary->getRandomWord();
        $this->assertContains($word, ['symfony', 'php', 'twig']);
    }
}
