<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Interface\DictionaryInterface;

class ConstantWordProvider implements DictionaryInterface
{
    public function getRandomWord(): string
    {
        return 'ruisseau';
    }

    /** @return list<string> */
    public function getWordsByLength(int $length): array
    {
        return [$this->getRandomWord()];
    }
}
