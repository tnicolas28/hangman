<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use App\Domain\Enum\GameStatus;
use App\Infrastructure\Entity\Trait\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

class GameEntity
{
    use TimestampableTrait;

    private Uuid $id;

    private string $word;

    /** @var list<string> */
    private array $guessedLetters = [];

    /** @var list<string> */
    private array $usedLetters = [];

    private int $tries = 0;

    private bool $hintUsed = false;

    private GameStatus $status = GameStatus::Playing;

    private \DateTimeImmutable $startedAt;

    public function __construct(string $word = '', ?Uuid $id = null, ?\DateTimeImmutable $startedAt = null)
    {
        $this->id = $id ?? Uuid::v7();
        $this->word = $word;
        $this->startedAt = $startedAt ?? new \DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    /** @return list<string> */
    public function getGuessedLetters(): array
    {
        return $this->guessedLetters;
    }

    /** @return list<string> */
    public function getUsedLetters(): array
    {
        return $this->usedLetters;
    }

    public function getTries(): int
    {
        return $this->tries;
    }

    public function getHintUsage(): bool
    {
        return $this->hintUsed;
    }

    public function getStatus(): GameStatus
    {
        return $this->status;
    }

    /** @param list<string> $guessedLetters */
    public function setGuessedLetters(array $guessedLetters): void
    {
        $this->guessedLetters = $guessedLetters;
    }

    /** @param list<string> $usedLetters */
    public function setUsedLetters(array $usedLetters): void
    {
        $this->usedLetters = $usedLetters;
    }

    public function setTries(int $tries): void
    {
        $this->tries = $tries;
    }

    public function setStatus(GameStatus $status): void
    {
        $this->status = $status;
    }

    public function setHintUsed(bool $hintUsed): void
    {
        $this->hintUsed = $hintUsed;
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }
}
