<?php

declare(strict_types=1);

namespace App\Patient\Infrastructure\Repository;

use App\Patient\Domain\Entity\Patient;
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

    /** @return Patient[] */
    public function findWithTreatments(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.treatments', 't')
            ->addSelect('t')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(Patient $patient, bool $flush = false): void
    {
        $this->getEntityManager()->persist($patient);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Patient $patient, bool $flush = false): void
    {
        $this->getEntityManager()->remove($patient);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
