<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Event\GameFinishedEvent;
use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameNotifierInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsAlias(GameNotifierInterface::class)]
class SymfonyGameNotifier implements GameNotifierInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function notifyGameFinished(GameInterface $game): void
    {
        $this->dispatcher->dispatch(new GameFinishedEvent($game));
    }
}
