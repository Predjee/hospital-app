<?php

declare(strict_types=1);

namespace App\Audit\Application\Command;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
readonly class RecordActionCommand
{
    /** @param array<string, mixed> $payload */
    public function __construct(
        public string $action,
        public array $payload = [],
    ) {
    }
}
