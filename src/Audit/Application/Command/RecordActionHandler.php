<?php

declare(strict_types=1);

namespace App\Audit\Application\Command;

use App\Audit\Domain\Entity\AuditLog;
use App\Audit\Domain\Repository\AuditLogRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecordActionHandler
{
    public function __construct(private AuditLogRepository $repo)
    {
    }

    public function __invoke(RecordActionCommand $command): void
    {
        $log = new AuditLog($command->action, $command->payload);
        $this->repo->save($log);
    }
}
