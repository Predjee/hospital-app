<?php

declare(strict_types=1);

namespace App\Treatment\Application\Command;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Ulid;

#[AsMessage]
readonly class StartTreatmentCommand
{
    public function __construct(public Ulid $treatmentId)
    {
    }
}
