<?php

declare(strict_types=1);

namespace App\Doctor\Domain\Repository;

use App\Department\Domain\Entity\Department;
use App\Doctor\Domain\Entity\Doctor;

interface DoctorRepository
{
    public function get(string $id): Doctor;

    /** @return Doctor[] */
    public function findAll(): array;

    public function save(Doctor $doctor): void;

    public function remove(Doctor $doctor): void;

    /** @return Doctor[] */
    public function findByDepartment(Department $department): array;
}
