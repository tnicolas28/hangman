<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Enum\GameStatus;
use App\Domain\Interface\GameInterface;
use App\Domain\Interface\GameRepositoryInterface;
use App\Domain\Model\EvilGame;
use App\Domain\Model\Game;
use App\Infrastructure\Entity\EvilGameEntity;
use App\Infrastructure\Entity\GameEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Uid\Uuid;

#[AsAlias(GameRepositoryInterface::class)]
class DoctrineGameRepository implements GameRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function find(Uuid $id): ?GameInterface
    {
        $entity = $this->entityManager->find(GameEntity::class, $id);

        if (null === $entity) {
            return null;
        }

        return $this->entityToModel($entity);
    }

    /** @return list<GameInterface> */
    public function findInProgress(): array
    {
        $entities = $this->entityManager->getRepository(GameEntity::class)
            ->findBy(['status' => GameStatus::Playing], ['createdAt' => 'DESC']);

        return \array_map(fn (GameEntity $entity) => $this->entityToModel($entity), $entities);
    }

    public function save(GameInterface $game): void
    {
        $entity = $this->entityManager->find(GameEntity::class, $game->getId());

        if (null === $entity) {
            $entity = $this->modelToNewEntity($game);
            $this->entityManager->persist($entity);
        } else {
            $this->updateEntityFromModel($entity, $game);
        }

        $this->entityManager->flush();
    }

    private function entityToModel(GameEntity $entity): GameInterface
    {
        if ($entity instanceof EvilGameEntity) {
            return new EvilGame(
                $entity->getCandidates(),
                guessedLetters: $entity->getGuessedLetters(),
                usedLetters: $entity->getUsedLetters(),
                tries: $entity->getTries(),
                hintUsed: $entity->getHintUsage(),
                id: $entity->getId(),
            );
        }

        return new Game(
            $entity->getWord(),
            $entity->getGuessedLetters(),
            $entity->getUsedLetters(),
            $entity->getTries(),
            hintUsed: $entity->getHintUsage(),
            id: $entity->getId(),
        );
    }

    private function modelToNewEntity(GameInterface $game): GameEntity
    {
        if ($game instanceof EvilGame) {
            return new EvilGameEntity($game->getCandidates(), $game->getId());
        }

        if ($game instanceof Game) {
            return new GameEntity($game->getWordToGuess(), $game->getId());
        }

        throw new \InvalidArgumentException('Unknown game type');
    }

    private function updateEntityFromModel(GameEntity $entity, GameInterface $game): void
    {
        $entity->setGuessedLetters($game->getGuessedLetters());
        $entity->setUsedLetters($game->getUsedLetters());
        $entity->setTries($game->getTries());
        $entity->setStatus($game->getStatus());
        $entity->setHintUsed($game->getHintUsage());

        if ($entity instanceof EvilGameEntity && $game instanceof EvilGame) {
            $entity->setCandidates($game->getCandidates());
        }
    }
}
