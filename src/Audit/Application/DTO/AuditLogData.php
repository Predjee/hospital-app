<?php

declare(strict_types=1);

namespace App\Audit\Application\DTO;

final readonly class AuditLogData
{
    /** @param array<string, mixed> $payload */
    public function __construct(
        public string $action,
        public array $payload,
        public \DateTimeImmutable $recordedAt,
    ) {
    }
}
