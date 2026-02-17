<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum GameStatus: string
{
    case Playing = 'playing';
    case Won = 'won';
    case Lost = 'lost';
}
