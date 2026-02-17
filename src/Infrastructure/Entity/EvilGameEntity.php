<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'evil_games')]
class EvilGameEntity extends GameEntity
{
    /** @var list<string> */
    #[ORM\Column(type: 'json')]
    private array $candidates = [];

    /** @param list<string> $candidates */
    public function __construct(array $candidates, ?Uuid $id = null)
    {
        parent::__construct('', $id);
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
