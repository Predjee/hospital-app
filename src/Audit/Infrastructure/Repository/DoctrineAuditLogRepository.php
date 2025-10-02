<?php

declare(strict_types=1);

namespace App\Audit\Infrastructure\Repository;

use App\Audit\Domain\Entity\AuditLog;
use App\Audit\Domain\Repository\AuditLogRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineAuditLogRepository implements AuditLogRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(AuditLog $log): void
    {
        $this->em->persist($log);
        $this->em->flush();
    }

    public function findRecent(int $limit = 50): array
    {
        return $this->em->getRepository(AuditLog::class)
            ->createQueryBuilder('a')
            ->orderBy('a.recordedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
