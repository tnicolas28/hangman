<?php

namespace App\Domain\Interface;

use Symfony\Component\Uid\Uuid;

interface GameRepositoryInterface
{
    public function find(Uuid $id): ?GameInterface;
    public function save(GameInterface $game): void;
    /** @return GameInterface[] */
    public function findInProgress(): array;
}
