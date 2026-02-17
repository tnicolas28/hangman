<?php

declare(strict_types=1);

namespace App\Domain\Interface;

interface DictionaryInterface
{
    public function getRandomWord(): string;

    /** @return list<string> */
    public function getWordsByLength(int $length): array;
}
