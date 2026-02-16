<?php

namespace App\Domain\Model;

use App\Domain\Enum\GameStatus;
use App\Domain\Interface\GameInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Uid\Uuid;

#[AsAlias(GameInterface::class)]
class Game implements GameInterface
{
    private Uuid $id;

    public function __construct(
        private string $wordToGuess,
        private array $guessedLetters = [],
        private array $usedLetters = [],
        private int $tries = 0,
        private int $maxTries = 6,
        private bool $hintUsed = false,
        ?Uuid $id = null,
    ){
        $this->id = $id ?? Uuid::v7();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getWordToGuess(): string
    {
        return $this->wordToGuess;
    }

    public function getGuessedLetters(): array
    {
        return $this->guessedLetters;
    }

    public function getUsedLetters(): array
    {
        return $this->usedLetters;
    }

    public function getTries(): int
    {
        return $this->tries;
    }

    public function getMaskedWord(): string
    {
        $masked = '';
        foreach (str_split($this->wordToGuess) as $letter) {
            $masked .= in_array($letter, $this->guessedLetters) ? $letter : '_';
        }

        return $masked;
    }

    public function guess(string $letter): void {
        //Si lettre déjà utilisée
        if(in_array($letter, $this->guessedLetters) || in_array($letter, $this->usedLetters)) {
            return;
        }

        if(str_contains($this->wordToGuess, $letter)) {
            $this->guessedLetters[] = $letter;
        } else {
            $this->usedLetters[] = $letter;
            $this->tries++;
        }
    }

    public function getStatus(): GameStatus
    {
        if ($this->lost()) {
            return GameStatus::Lost;
        }

        if ($this->won()) {
            return GameStatus::Won;
        }

        return GameStatus::Playing;
    }

    public function won(): bool
    {
        return array_all(str_split($this->wordToGuess), fn($letter) => in_array($letter, $this->guessedLetters));

    }

    public function lost(): bool
    {
        return $this->tries >= $this->maxTries;
    }

    public function getMaxTries(): int
    {
        return $this->maxTries;
    }

    public function isEvil(): bool
    {
        return false;
    }

    public function getHintUsage(): bool
    {
        return $this->hintUsed;
    }

    public function useHint(): void
    {
        if ($this->hintUsed) {
            return;
        }

        $this->hintUsed = true;

        $unguessedLetters = [];
        foreach (str_split($this->wordToGuess) as $letter) {
            if (!in_array($letter, $this->guessedLetters) && !in_array($letter, $unguessedLetters)) {
                $unguessedLetters[] = $letter;
            }
        }

        if ($unguessedLetters !== []) {
            $this->guessedLetters[] = $unguessedLetters[array_rand($unguessedLetters)];
        }
    }
}
