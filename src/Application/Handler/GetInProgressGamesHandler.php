<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameRepositoryInterface;

final readonly class GetInProgressGamesHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
    ) {
    }

    /** @return list<GameInterface> */
    public function __invoke(): array
    {
        return $this->gameRepository->findInProgress();
    }
}
