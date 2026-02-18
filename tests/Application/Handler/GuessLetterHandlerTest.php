<?php

declare(strict_types=1);

namespace App\Tests\Application\Handler;

use App\Application\Handler\CreateGameCommand;
use App\Application\Handler\CreateGameHandler;
use App\Application\Handler\GuessLetterCommand;
use App\Application\Handler\GuessLetterHandler;
use App\Domain\Enum\GameStatus;
use App\Domain\Interface\GameNotifierInterface;
use App\Infrastructure\Repository\InMemoryGameRepository;
use App\Infrastructure\Service\ConstantWordProvider;
use App\Infrastructure\Service\SystemClock;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class GuessLetterHandlerTest extends TestCase
{
    private CreateGameHandler $createGameHandler;
    private GuessLetterHandler $handler;
    private GameNotifierInterface&Stub $notifier;

    protected function setUp(): void
    {
        $repository = new InMemoryGameRepository();
        $this->notifier = $this->createStub(GameNotifierInterface::class);

        $this->createGameHandler = new CreateGameHandler(
            new ConstantWordProvider(),
            $repository,
            new SystemClock(),
        );

        $this->handler = new GuessLetterHandler(
            $repository,
            $this->notifier,
        );
    }

    public function testGuessWrongLetterIncrementsTries(): void
    {
        $game = ($this->createGameHandler)(new CreateGameCommand('normal'));

        ($this->handler)(new GuessLetterCommand($game, 'z'));

        $this->assertSame(1, $game->getTries());
        $this->assertContains('z', $game->getUsedLetters());
    }

    public function testGuessCorrectLetterReturnsTrue(): void
    {
        $game = ($this->createGameHandler)(new CreateGameCommand('normal'));

        ($this->handler)(new GuessLetterCommand($game, 'r'));

        $this->assertSame(0, $game->getTries());
        $this->assertContains('r', $game->getGuessedLetters());
    }

    public function testWinGame(): void
    {
        $game = ($this->createGameHandler)(new CreateGameCommand('normal'));

        foreach (\str_split('ruisseau') as $letter) {
            $game->guess($letter);
        }

        $this->assertSame(GameStatus::Won, $game->getStatus());
    }

    #[DataProvider('guessScenarios')]
    public function testGuessedLetters(
        string $letter,
        bool $expectedFound,
    ): void {
        $game = ($this->createGameHandler)(new CreateGameCommand('normal'));
        ($this->handler)(new GuessLetterCommand($game, $letter));

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
