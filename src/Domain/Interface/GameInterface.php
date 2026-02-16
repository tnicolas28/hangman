<?php

namespace App\Domain\Interface;

use App\Domain\Enum\GameStatus;
use Symfony\Component\Uid\Uuid;

interface GameInterface
{
    public function getId(): Uuid;

    public function guess(string $letter): void;

    public function getStatus(): GameStatus;

    public function won(): bool;

    public function lost(): bool;

    public function getGuessedLetters(): array;

    public function getUsedLetters(): array;

    public function getTries(): int;
}

