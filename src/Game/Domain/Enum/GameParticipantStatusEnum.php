<?php

namespace App\Game\Domain\Enum;

enum GameParticipantStatusEnum: string
{
    case NOT_READY = 'NOT_READY';
    case READY = 'READY';
    case WAITING_TURN = 'WAITING_TURN';
    case ACTIVE_TURN = 'ACTIVE_TURN';
    case DISCONNECTED = 'DISCONNECTED';
    case ELIMINATED = 'ELIMINATED';
}
