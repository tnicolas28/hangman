<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Interface\ClockInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(ClockInterface::class)]
class SystemClock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
