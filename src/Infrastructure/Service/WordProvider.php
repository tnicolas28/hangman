<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

// Classe qui charger le fichier de mot et en charger un
use App\Domain\Interface\DictionaryInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(DictionaryInterface::class)]
class WordProvider implements DictionaryInterface
{
    /** @var list<string> */
    private array $words;

    public function __construct(string $filePath)
    {
        $words = \file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->words = false !== $words ? $words : [];
    }

    public function getRandomWord(): string
    {
        return $this->words[\array_rand($this->words)];
    }

    /** @return list<string> */
    public function getWordsByLength(int $length): array
    {
        return \array_values(\array_filter($this->words, fn (string $word) => \mb_strlen($word) === $length));
    }
}
