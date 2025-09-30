<?php

declare(strict_types=1);

namespace App\Treatment\Application\DTO;

use App\Treatment\Domain\Enum\TreatmentType;
use Symfony\Component\Uid\Ulid;

final readonly class TreatmentSummaryData
{
    public function __construct(
        public Ulid $id,
        public TreatmentType $type,
        public bool $completed,
    ) {
    }
}
