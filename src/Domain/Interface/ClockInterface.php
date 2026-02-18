<?php

declare(strict_types=1);

namespace App\Domain\Interface;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}
