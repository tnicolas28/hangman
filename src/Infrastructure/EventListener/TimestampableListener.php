<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Domain\Interface\ClockInterface;
use App\Infrastructure\Entity\GameEntity;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, entity: GameEntity::class)]
#[AsEntityListener(event: Events::preUpdate, entity: GameEntity::class)]
class TimestampableListener
{
    public function __construct(
        private ClockInterface $clock,
    ) {
    }

    public function prePersist(GameEntity $entity): void
    {
        $entity->initCreatedAt($this->clock->now());
    }

    public function preUpdate(GameEntity $entity): void
    {
        $entity->updateUpdatedAt($this->clock->now());
    }
}
