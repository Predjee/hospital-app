<?php

declare(strict_types=1);

namespace App\Patient\Domain\Repository;

use App\Patient\Domain\Entity\Patient;
use Symfony\Component\Uid\Ulid;

interface PatientRepository
{
    /** @return Patient[] */
    public function findAll(): array;

    /** @return Patient[] */
    public function findWithTreatments(): array;

    public function find(Ulid $id): ?Patient;

    public function save(Patient $patient): void;
}
