<?php

declare(strict_types=1);

namespace App\Enum;

enum Department: string
{
    case RADIOLOGY = 'Radiologie';
    case SURGERY = 'Chirurgie';
    case PHYSIOTHERAPY = 'Fysiotherapie';
    case REHAB = 'Revalidatie';
}
