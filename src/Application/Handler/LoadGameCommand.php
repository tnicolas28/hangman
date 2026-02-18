<?php

declare(strict_types=1);

namespace App\Application\Handler;

use Symfony\Component\Uid\Uuid;

final readonly class LoadGameCommand
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}
