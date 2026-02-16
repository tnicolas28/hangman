<?php

namespace App\Infrastructure\Repository;

use App\Domain\Enum\GameStatus;
use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameRepositoryInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionGameRepository implements GameRepositoryInterface
{
    public function __construct(
        private SessionInterface $session
    ){}

    public function find(Uuid $id): ?GameInterface
    {
        $game = $this->session->get('game');

        return $game instanceof GameInterface ? $game : null;
    }

    public function save(GameInterface $game): void
    {
        $this->session->set('game', $game);
    }

    public function findInProgress(): array
    {
        $game = $this->session->get('game');

        if ($game instanceof GameInterface && $game->getStatus() === GameStatus::Playing) {
            return [$game];
        }

        return [];
    }
}
