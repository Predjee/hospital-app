<?php

declare(strict_types=1);

namespace App\Doctor\Infrastructure\Repository;

use App\Department\Domain\Entity\Department;
use App\Doctor\Domain\Entity\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Doctor>
 */
class DoctorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

    public function save(Doctor $doctor, bool $flush = false): void
    {
        $this->getEntityManager()->persist($doctor);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Doctor $doctor, bool $flush = false): void
    {
        $this->getEntityManager()->remove($doctor);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Doctor[]
     */
    public function findByDepartment(Department $department): array
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.departments', 'dep')
            ->andWhere('dep = :department')
            ->setParameter('department', $department)
            ->getQuery()
            ->getResult();
    }
}
