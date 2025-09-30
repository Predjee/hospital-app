<?php

declare(strict_types=1);

namespace App\Department\Infrastructure\Repository;

use App\Treatment\Domain\Entity\Treatment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Treatment>
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Treatment::class);
    }
}
