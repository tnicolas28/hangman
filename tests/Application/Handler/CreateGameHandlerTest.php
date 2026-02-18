<?php

declare(strict_types=1);

namespace App\Tests\Application\Handler;

use App\Application\Handler\CreateGameCommand;
use App\Application\Handler\CreateGameHandler;
use App\Domain\Model\Game;
use App\Infrastructure\Repository\InMemoryGameRepository;
use App\Infrastructure\Service\ConstantWordProvider;
use App\Infrastructure\Service\SystemClock;
use PHPUnit\Framework\TestCase;

class CreateGameHandlerTest extends TestCase
{
    private CreateGameHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new CreateGameHandler(
            new ConstantWordProvider(),
            new InMemoryGameRepository(),
            new SystemClock(),
        );
    }

    public function testCreateNewGame(): void
    {
        $game = ($this->handler)(new CreateGameCommand('normal'));

        $this->assertInstanceOf(Game::class, $game);
        $this->assertSame('ruisseau', $game->getWordToGuess());
    }

    public function testCorrectWord(): void
    {
        $game = ($this->handler)(new CreateGameCommand('normal'));

        $this->assertSame('ruisseau', $game->getWordToGuess());
    }
}
