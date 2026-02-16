<?php

namespace App\Infrastructure\Entity;

use App\Infrastructure\Entity\Trait\TimestampableTrait;
use App\Domain\Enum\GameStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'games')]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'standard' => GameEntity::class,
    'evil' => EvilGameEntity::class,
])]
#[ORM\HasLifecycleCallbacks]
class GameEntity
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(length: 100)]
    private string $word;

    #[ORM\Column(type: 'json')]
    private array $guessedLetters = [];

    #[ORM\Column(type: 'json')]
    private array $usedLetters = [];

    #[ORM\Column(type: 'integer')]
    private int $tries = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $hintUsed = false;

    #[ORM\Column(enumType: GameStatus::class)]
    private GameStatus $status = GameStatus::Playing;

    public function __construct(string $word = '', ?Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v7();
        $this->word = $word;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function getGuessedLetters(): array
    {
        return $this->guessedLetters;
    }

    public function getUsedLetters(): array
    {
        return $this->usedLetters;
    }

    public function getTries(): int
    {
        return $this->tries;
    }

    public function getHintUsage(): bool
    {
        return $this->hintUsed;
    }

    public function getStatus(): GameStatus
    {
        return $this->status;
    }

    public function setGuessedLetters(array $guessedLetters): void
    {
        $this->guessedLetters = $guessedLetters;
    }

    public function setUsedLetters(array $usedLetters): void
    {
        $this->usedLetters = $usedLetters;
    }

    public function setTries(int $tries): void
    {
        $this->tries = $tries;
    }

    public function setStatus(GameStatus $status): void
    {
        $this->status = $status;
    }
}
