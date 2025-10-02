<?php

declare(strict_types=1);

namespace App\Audit\Domain\Repository;

use App\Audit\Domain\Entity\AuditLog;

interface AuditLogRepository
{
    public function save(AuditLog $log): void;

    /** @return AuditLog[] */
    public function findRecent(int $limit = 50): array;
}
