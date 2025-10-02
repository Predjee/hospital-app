<?php

declare(strict_types=1);

namespace App\Department\Infrastructure\Repository;

use App\Department\Domain\Entity\Department;
use App\Department\Domain\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineDepartmentRepository implements DepartmentRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function get(string $id): Department
    {
        $department = $this->em->find(Department::class, $id);

        if (!$department) {
            throw new \RuntimeException("Department {$id} not found.");
        }

        return $department;
    }

    public function findAll(): array
    {
        return $this->em->getRepository(Department::class)->findAll();
    }

    public function save(Department $department): void
    {
        $this->em->persist($department);
        $this->em->flush();
    }

    public function remove(Department $department): void
    {
        $this->em->remove($department);
        $this->em->flush();
    }
}
