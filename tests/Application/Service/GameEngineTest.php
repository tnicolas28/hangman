<?php

declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Application\Service\GameEngine;
use App\Domain\Enum\GameStatus;
use App\Domain\Interface\GameNotifierInterface;
use App\Domain\Model\Game;
use App\Infrastructure\Repository\InMemoryGameRepository;
use App\Infrastructure\Service\ConstantWordProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class GameEngineTest extends TestCase
{
    private ConstantWordProvider $dictionary;
    private InMemoryGameRepository $gameRepository;
    private GameNotifierInterface&Stub $notifier;
    private GameEngine $engine;

    protected function setUp(): void
    {
        $this->dictionary = new ConstantWordProvider();
        $this->gameRepository = new InMemoryGameRepository();
        $this->notifier = $this->createStub(GameNotifierInterface::class);

        $this->engine = new GameEngine(
            $this->dictionary,
            $this->gameRepository,
            $this->notifier,
        );
    }

    public function testCreateNewGame(): void
    {
        $game = $this->engine->createGame('normal');

        $this->assertInstanceOf(Game::class, $game);
        $this->assertSame('ruisseau', $game->getWordToGuess());
    }

    public function testGuessWrongLetterIncrementsTries(): void
    {
        $game = $this->engine->createGame('normal');

        $this->engine->guess($game, 'z');

        $this->assertSame(1, $game->getTries());
        $this->assertContains('z', $game->getUsedLetters());
    }

    public function testGuessCorrectLetterReturnsTrue(): void
    {
        $game = $this->engine->createGame('normal');

        $this->engine->guess($game, 'r');

        $this->assertSame(0, $game->getTries());
        $this->assertContains('r', $game->getGuessedLetters());
    }

    public function testWinGame(): void
    {
        $game = $this->engine->createGame('normal');

        foreach (\str_split('ruisseau') as $letter) {
            $game->guess($letter);
        }

        $this->assertSame(GameStatus::Won, $game->getStatus());
    }

    public function testCorrectWord(): void
    {
        $game = $this->engine->createGame('normal');

        $this->assertSame('ruisseau', $game->getWordToGuess());
    }

    #[DataProvider('guessScenarios')]
    public function testGuessedLetters(
        string $letter,
        bool $expectedFound,
    ): void {
        $game = $this->engine->createGame('normal');
        $this->engine->guess($game, $letter);

        if ($expectedFound) {
            $this->assertContains(\strtolower($letter), $game->getGuessedLetters());
        } else {
            $this->assertContains(\strtolower($letter), $game->getUsedLetters());
        }
    }

    /** @return iterable<string, array{string, bool}> */
    public static function guessScenarios(): iterable
    {
        yield 'lettre presente' => ['e', true];
        yield 'lettre absente' => ['m', false];
        yield 'lettre majuscule' => ['R', true];
        yield 'lettre en double' => ['s', true];
    }
}
