<?php

declare(strict_types=1);

namespace App\Doctor\Infrastructure\Repository;

use App\Department\Domain\Entity\Department;
use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineDoctorRepository implements DoctorRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function get(string $id): Doctor
    {
        $doctor = $this->em->find(Doctor::class, $id);

        if (!$doctor) {
            throw new \RuntimeException("Doctor {$id} not found.");
        }

        return $doctor;
    }

    public function findAll(): array
    {
        return $this->em->getRepository(Doctor::class)->findAll();
    }

    public function save(Doctor $doctor): void
    {
        $this->em->persist($doctor);
        $this->em->flush();
    }

    public function remove(Doctor $doctor): void
    {
        $this->em->remove($doctor);
        $this->em->flush();
    }

    public function findByDepartment(Department $department): array
    {
        return $this->em->createQueryBuilder()
            ->select('d')
            ->from(Doctor::class, 'd')
            ->innerJoin('d.departments', 'dep')
            ->andWhere('dep = :department')
            ->setParameter('department', $department)
            ->getQuery()
            ->getResult();
    }
}
