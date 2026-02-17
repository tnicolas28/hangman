<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Domain\Event\GameFinishedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class GameFinishedListener
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(GameFinishedEvent $event): void
    {
        $game = $event->game;
        $status = $game->won() ? 'gagnée' : 'perdue';

        $this->logger->info('Partie {status} en {tries}/{maxTries} essais.', [
            'status' => $status,
            'tries' => $game->getTries(),
            'maxTries' => $game->getMaxTries(),
        ]);
    }
}
