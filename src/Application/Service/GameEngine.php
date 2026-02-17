<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Interface\DictionaryInterface;
use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameNotifierInterface;
use App\Domain\Interface\GameRepositoryInterface;
use App\Domain\Model\EvilGame;
use App\Domain\Model\Game;
use Symfony\Component\Uid\Uuid;

class GameEngine
{
    private const MAX_TRIES = 6;

    public function __construct(
        private DictionaryInterface $dictionary,
        private GameRepositoryInterface $gameRepository,
        private GameNotifierInterface $notifier,
    ) {
    }

    public function createGame(string $mode): GameInterface
    {
        if ('evil' === $mode) {
            $word = $this->dictionary->getRandomWord();
            $candidates = $this->dictionary->getWordsByLength(\mb_strlen($word));
            $game = new EvilGame($candidates, self::MAX_TRIES);
        } else {
            $word = $this->dictionary->getRandomWord();
            $game = new Game($word);
        }

        $this->gameRepository->save($game);

        return $game;
    }

    /** @return list<GameInterface> */
    public function getInProgressGames(): array
    {
        return $this->gameRepository->findInProgress();
    }

    public function loadGame(Uuid $id): ?GameInterface
    {
        return $this->gameRepository->find($id);
    }

    public function guess(GameInterface $game, string $letter): void
    {
        $game->guess(\strtolower($letter[0]));
        $this->gameRepository->save($game);

        if ($game->won() || $game->lost()) {
            $this->notifier->notifyGameFinished($game);
        }
    }

    public function useHint(GameInterface $game): void
    {
        $game->useHint();
        $this->gameRepository->save($game);
    }
}
