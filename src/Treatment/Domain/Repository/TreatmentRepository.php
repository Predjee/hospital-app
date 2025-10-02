<?php

declare(strict_types=1);

namespace App\Treatment\Domain\Repository;

use App\Treatment\Domain\Entity\Treatment;
use Symfony\Component\Uid\Ulid;

interface TreatmentRepository
{
    public function get(Ulid $id): Treatment;

    /** @return Treatment[] */
    public function findAll(): array;

    public function save(Treatment $treatment): void;

    public function remove(Treatment $treatment): void;
}
