<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Interface\GameInterface;

final readonly class UseHintCommand
{
    public function __construct(
        public GameInterface $game,
    ) {
    }
}
