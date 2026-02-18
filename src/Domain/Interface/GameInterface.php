<?php

declare(strict_types=1);

namespace App\Domain\Interface;

use App\Domain\Enum\GameStatus;
use Symfony\Component\Uid\Uuid;

interface GameInterface
{
    public Uuid $id {
        get;
    }

    public function guess(string $letter): void;

    public function getStatus(): GameStatus;

    public function won(): bool;

    public function lost(): bool;

    /** @return list<string> */
    public function getGuessedLetters(): array;

    /** @return list<string> */
    public function getUsedLetters(): array;

    public function getTries(): int;

    public function getMaxTries(): int;

    public function getMaskedWord(): string;

    public function getWordToGuess(): string;

    public function useHint(): void;

    public function getHintUsage(): bool;

    public function getStartedAt(): \DateTimeImmutable;

    public function startedSince(ClockInterface $clock): \DateInterval;
}
