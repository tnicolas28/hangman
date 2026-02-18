<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Interface\ClockInterface;
use App\Domain\Interface\DictionaryInterface;
use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameRepositoryInterface;
use App\Domain\Model\EvilGame;
use App\Domain\Model\Game;

final readonly class CreateGameHandler
{
    private const MAX_TRIES = 6;

    public function __construct(
        private DictionaryInterface $dictionary,
        private GameRepositoryInterface $gameRepository,
        private ClockInterface $clock,
    ) {
    }

    public function __invoke(CreateGameCommand $command): GameInterface
    {
        $now = $this->clock->now();

        if ('evil' === $command->mode) {
            $word = $this->dictionary->getRandomWord();
            $candidates = $this->dictionary->getWordsByLength(\mb_strlen($word));
            $game = new EvilGame($candidates, $now, self::MAX_TRIES);
        } else {
            $word = $this->dictionary->getRandomWord();
            $game = new Game($word, $now);
        }

        $this->gameRepository->save($game);

        return $game;
    }
}
