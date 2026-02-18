<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Interface\GameRepositoryInterface;

final readonly class UseHintHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
    ) {
    }

    public function __invoke(UseHintCommand $command): void
    {
        $command->game->useHint();
        $this->gameRepository->save($command->game);
    }
}
