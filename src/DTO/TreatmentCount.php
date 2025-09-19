<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\TreatmentType;

final readonly class TreatmentCount
{
    public function __construct(
        public TreatmentType $type,
        public int $count,
    ) {
    }
}
