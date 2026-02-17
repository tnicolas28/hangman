<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Model\Game;
use App\Infrastructure\Repository\InMemoryGameRepository;
use PHPUnit\Framework\TestCase;

class InMemoryGameRepositoryTest extends TestCase
{
    private InMemoryGameRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryGameRepository();
    }

    public function testSaveAndFind(): void
    {
        $game = new Game('symfony');
        $this->repository->save($game);

        $found = $this->repository->find($game->getId());

        $this->assertSame($game, $found);
    }

    public function testFindReturnsNullWhenNotFound(): void
    {
        $game = new Game('symfony');

        $found = $this->repository->find($game->getId());

        $this->assertNull($found);
    }
}
