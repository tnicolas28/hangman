<?php

declare(strict_types=1);

namespace App\Application\Handler;

final readonly class CreateGameCommand
{
    public function __construct(
        public string $mode,
    ) {
    }
}
