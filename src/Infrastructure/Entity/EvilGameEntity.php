<?php

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'evil_games')]
class EvilGameEntity extends GameEntity
{
    #[ORM\Column(type: 'json')]
    private array $candidates = [];

    public function __construct(array $candidates, ?Uuid $id = null)
    {
        parent::__construct('', $id);
        $this->candidates = $candidates;
    }

    public function getCandidates(): array
    {
        return $this->candidates;
    }

    public function setCandidates(array $candidates): void
    {
        $this->candidates = $candidates;
    }
}
