<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use Symfony\Component\Uid\Uuid;

class EvilGameEntity extends GameEntity
{
    /** @var list<string> */
    private array $candidates = [];

    /** @param list<string> $candidates */
    public function __construct(array $candidates, ?Uuid $id = null, ?\DateTimeImmutable $startedAt = null)
    {
        parent::__construct('', $id, $startedAt);
        $this->candidates = $candidates;
    }

    /** @return list<string> */
    public function getCandidates(): array
    {
        return $this->candidates;
    }

    /** @param list<string> $candidates */
    public function setCandidates(array $candidates): void
    {
        $this->candidates = $candidates;
    }
}
