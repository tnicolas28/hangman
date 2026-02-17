<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Application\Service\GameEngine;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:play-hangman', description: 'Jouer au pendu en CLI')]
class PlayHangmanCommand extends Command
{
    public function __construct(
        private readonly GameEngine $engine,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        do {
            $this->playGame($io);
        } while ($io->confirm('Relancer une partie ?'));

        return Command::SUCCESS;
    }

    private function playGame(SymfonyStyle $io): void
    {
        /** @var string $mode */
        $mode = $io->choice('Choisissez un mode', ['normal' => 'Mode Normal', 'evil' => 'Mode Diabolique'], 'normal');
        $game = $this->engine->createGame($mode);

        while (!$game->won() && !$game->lost()) {
            $io->writeln(\sprintf('Mot : %s    (essais : %d/%d)', $game->getMaskedWord(), $game->getTries(), $game->getMaxTries()));

            if ([] !== $game->getUsedLetters()) {
                $io->writeln('Lettres utilisées : '.\implode(', ', $game->getUsedLetters()));
            }

            $letter = $io->ask('Proposez une lettre');

            if (!\is_string($letter) || '' === $letter) {
                continue;
            }

            $this->engine->guess($game, $letter);
        }

        if ($game->won()) {
            $io->success('Gagné ! Le mot était : '.$game->getMaskedWord());
        } else {
            $io->error('Perdu ! Le mot était : '.$game->getWordToGuess());
        }
    }
}
