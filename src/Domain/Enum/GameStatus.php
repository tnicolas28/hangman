<?php

namespace App\Domain\Enum;

enum GameStatus: string{
    case Playing = 'playing';
    case Won = 'won';
    case Lost = 'lost';
}
