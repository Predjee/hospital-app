<?php

declare(strict_types=1);

namespace App\Department\Domain\Repository;

use App\Department\Domain\Entity\Department;

interface DepartmentRepository
{
    public function get(string $id): Department;

    /** @return Department[] */
    public function findAll(): array;

    public function save(Department $department): void;

    public function remove(Department $department): void;
}
