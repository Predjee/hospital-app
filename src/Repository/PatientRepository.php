<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\PatientSummaryDTO;
use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    /**
     * @return PatientSummaryDTO[]
     */
    public function findSummaries(): array
    {
        return $this->createQueryBuilder('p')
            ->select('NEW '.PatientSummaryDTO::class.'(p.id, p.name, p.status, p.birthDate)')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function add(Patient $patient, bool $flush = true): void
    {
        $this->getEntityManager()->persist($patient);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Patient $patient, bool $flush = true): void
    {
        $this->getEntityManager()->remove($patient);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
