<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Enum\GameStatus;
use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class InMemoryGameRepository implements GameRepositoryInterface
{
    /** @var array<string, GameInterface> */
    private array $games = [];

    public function find(Uuid $id): ?GameInterface
    {
        return $this->games[$id->toString()] ?? null;
    }

    public function save(GameInterface $game): void
    {
        $this->games[$game->getId()->toString()] = $game;
    }

    /** @return list<GameInterface> */
    public function findInProgress(): array
    {
        return \array_values(\array_filter(
            $this->games,
            fn (GameInterface $game) => GameStatus::Playing === $game->getStatus()
        ));
    }
}
