<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Interface\GameInterface;

final readonly class GuessLetterCommand
{
    public function __construct(
        public GameInterface $game,
        public string $letter,
    ) {
    }
}
