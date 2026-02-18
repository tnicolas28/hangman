<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Interface\GameNotifierInterface;
use App\Domain\Interface\GameRepositoryInterface;

final readonly class GuessLetterHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
        private GameNotifierInterface $notifier,
    ) {
    }

    public function __invoke(GuessLetterCommand $command): void
    {
        $command->game->guess(\strtolower($command->letter[0]));
        $this->gameRepository->save($command->game);

        if ($command->game->won() || $command->game->lost()) {
            $this->notifier->notifyGameFinished($command->game);
        }
    }
}
