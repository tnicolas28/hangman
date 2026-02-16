<?php

namespace App\Domain\Interface;

interface DictionaryInterface
{
    public function getRandomWord(): string;

    public function getWordsByLength(int $length): array;
}
