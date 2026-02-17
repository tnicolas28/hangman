<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Enum\GameStatus;
use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameRepositoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Uid\Uuid;

class SessionGameRepository implements GameRepositoryInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {
    }

    public function find(Uuid $id): ?GameInterface
    {
        $game = $this->session->get('game');

        return $game instanceof GameInterface ? $game : null;
    }

    public function save(GameInterface $game): void
    {
        $this->session->set('game', $game);
    }

    /** @return list<GameInterface> */
    public function findInProgress(): array
    {
        $game = $this->session->get('game');

        if ($game instanceof GameInterface && GameStatus::Playing === $game->getStatus()) {
            return [$game];
        }

        return [];
    }
}
