<?php

namespace App\Domain\Model;

use App\Domain\Enum\GameStatus;
use App\Domain\Interface\GameInterface;
use Symfony\Component\Uid\Uuid;

class EvilGame implements GameInterface
{
    private Uuid $id;
    private array $candidates;
    private int $wordLength;
    private array $guessedLetters;
    private array $usedLetters;
    private int $tries;
    private int $maxTries;

    public function __construct(
        array $candidates,
        int $maxTries = 6,
        array $guessedLetters = [],
        array $usedLetters = [],
        int $tries = 0,
        ?Uuid $id = null,
    ) {
        $this->id = $id ?? Uuid::v7();
        $this->candidates = array_values($candidates);
        $this->wordLength = mb_strlen($this->candidates[0]);
        $this->maxTries = $maxTries;
        $this->guessedLetters = $guessedLetters;
        $this->usedLetters = $usedLetters;
        $this->tries = $tries;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCandidates(): array
    {
        return $this->candidates;
    }

    public function getWordToGuess(): string
    {
        return $this->candidates[0];
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
        $word = $this->candidates[0];
        $masked = '';
        foreach (mb_str_split($word) as $letter) {
            $masked .= in_array($letter, $this->guessedLetters) ? $letter : '_';
        }

        return $masked;
    }

    public function guess(string $letter): void
    {
        if (in_array($letter, $this->guessedLetters) || in_array($letter, $this->usedLetters)) {
            return;
        }

        $partitions = $this->partition($letter);

        // Choisir le groupe de candidat le plus difficile => avec le plus d'underscore
        $bestPattern = null;
        $bestGroup = [];
        $bestSize = -1;

        foreach ($partitions as $pattern => $group) {
            $size = count($group);
            if ($size > $bestSize || ($size === $bestSize && $this->countUnderscores($pattern) > $this->countUnderscores($bestPattern))) {
                $bestPattern = $pattern;
                $bestGroup = $group;
                $bestSize = $size;
            }
        }

        $this->candidates = $bestGroup;

        if (!str_contains($bestPattern, $letter)) {
            $this->usedLetters[] = $letter;
            $this->tries++;
        } else {
            $this->guessedLetters[] = $letter;
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
        foreach (mb_str_split($this->candidates[0]) as $letter) {
            if (!in_array($letter, $this->guessedLetters)) {
                return false;
            }
        }

        return true;
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
        return true;
    }

    /**
     * Partitionne les candidats selon le pattern produit par la lettre donnée.
     * Pattern = le mot masqué ne montrant que les positions de $letter + les lettres déjà devinées.
     */
    private function partition(string $letter): array
    {
        $partitions = [];

        foreach ($this->candidates as $word) {
            $pattern = '';
            $char_in_word = mb_str_split($word);
            foreach ($char_in_word as $char) {
                if ($char === $letter || in_array($char, $this->guessedLetters)) {
                    $pattern .= $char;
                } else {
                    $pattern .= '_';
                }
            }
            $partitions[$pattern][] = $word;
        }

        return $partitions;
    }

    private function countUnderscores(?string $pattern): int
    {
        if ($pattern === null) {
            return 0;
        }

        return substr_count($pattern, '_');
    }
}
