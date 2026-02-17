<?php

declare(strict_types=1);

namespace App\Domain\Interface;

interface GameNotifierInterface
{
    public function notifyGameFinished(GameInterface $game): void;
}
