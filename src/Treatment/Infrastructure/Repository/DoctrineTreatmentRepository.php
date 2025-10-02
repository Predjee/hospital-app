<?php

declare(strict_types=1);

namespace App\Treatment\Infrastructure\Repository;

use App\Treatment\Domain\Entity\Treatment;
use App\Treatment\Domain\Repository\TreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

final class DoctrineTreatmentRepository implements TreatmentRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function get(Ulid $id): Treatment
    {
        $treatment = $this->em->find(Treatment::class, $id);

        if (!$treatment) {
            throw new \RuntimeException("Treatment {$id} not found.");
        }

        return $treatment;
    }

    public function findAll(): array
    {
        return $this->em->getRepository(Treatment::class)->findAll();
    }

    public function save(Treatment $treatment): void
    {
        $this->em->persist($treatment);
        $this->em->flush();
    }

    public function remove(Treatment $treatment): void
    {
        $this->em->remove($treatment);
        $this->em->flush();
    }
}
