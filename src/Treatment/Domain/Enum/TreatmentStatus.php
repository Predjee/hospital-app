<?php

declare(strict_types=1);

namespace App\Treatment\Domain\Enum;

enum TreatmentStatus: string
{
    case PLANNED = 'planned';
    case STARTED = 'started';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
}
