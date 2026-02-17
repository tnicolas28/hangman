<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Interface\GameInterface;

class GameFinishedEvent
{
    public function __construct(
        public readonly GameInterface $game,
    ) {
    }
}
