<?php

declare(strict_types=1);

namespace App\Patient\Application\Command;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
readonly class AdmitPatientCommand
{
    public function __construct(
        public string $name,
        public \DateTimeImmutable $birthDate,
    ) {
    }
}
