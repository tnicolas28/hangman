<?php
namespace App\Infrastructure\Service;

//Classe qui charger le fichier de mot et en charger un
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use App\Domain\Interface\DictionaryInterface;

#[AsAlias(DictionaryInterface::class)]
class WordProvider implements DictionaryInterface
{
    private array $words;

    public function __construct(string $projectDir)
    {
        $this->words = file($projectDir . '/data/words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    public function getRandomWord(): string
    {
        return $this->words[array_rand($this->words)];
    }

    public function getWordsByLength(int $length): array
    {
        return array_values(array_filter($this->words, fn(string $word) => mb_strlen($word) === $length));
    }
}
