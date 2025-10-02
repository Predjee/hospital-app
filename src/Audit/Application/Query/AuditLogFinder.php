<?php

declare(strict_types=1);

namespace App\Audit\Application\Query;

use App\Audit\Application\DTO\AuditLogData;
use App\Audit\Domain\Repository\AuditLogRepository;

final readonly class AuditLogFinder
{
    public function __construct(private AuditLogRepository $repo)
    {
    }

    /** @return AuditLogData[] */
    public function recent(int $limit = 50): array
    {
        return array_map(
            fn ($log) => new AuditLogData(
                action: $log->action(),
                payload: $log->payload(),
                recordedAt: $log->recordedAt()
            ),
            $this->repo->findRecent($limit)
        );
    }
}
