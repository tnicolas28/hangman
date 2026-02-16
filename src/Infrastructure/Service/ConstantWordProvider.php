<?php

namespace App\Infrastructure\Service;

use App\Domain\Interface\DictionaryInterface;

class ConstantWordProvider implements DictionaryInterface
{
    public function getRandomWord(): string
    {
        return "ruisseau";
    }

    public function getWordsByLength(int $length): array
    {
        return [$this->getRandomWord()];
    }
}
