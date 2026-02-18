<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity\Trait;

trait TimestampableTrait
{
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt = null;

    public function initCreatedAt(\DateTimeImmutable $now): void
    {
        $this->createdAt = $now;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updateUpdatedAt(\DateTimeImmutable $now): void
    {
        $this->updatedAt = $now;
    }
}
