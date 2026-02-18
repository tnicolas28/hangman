<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameRepositoryInterface;

final readonly class LoadGameHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
    ) {
    }

    public function __invoke(LoadGameCommand $command): ?GameInterface
    {
        return $this->gameRepository->find($command->id);
    }
}
