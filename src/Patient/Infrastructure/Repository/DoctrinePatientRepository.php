<?php

declare(strict_types=1);

namespace App\Patient\Infrastructure\Repository;

use App\Patient\Domain\Entity\Patient;
use App\Patient\Domain\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

final readonly class DoctrinePatientRepository implements PatientRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findAll(): array
    {
        return $this->em->getRepository(Patient::class)->findAll();
    }

    public function findWithTreatments(): array
    {
        return $this->em->createQueryBuilder()
            ->select('p', 't')
            ->from(Patient::class, 'p')
            ->leftJoin('p.treatments', 't')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function find(Ulid $id): ?Patient
    {
        return $this->em->find(Patient::class, $id);
    }

    public function save(Patient $patient): void
    {
        $this->em->persist($patient);
        $this->em->flush();
    }

    public function remove(Patient $patient): void
    {
        $this->em->remove($patient);
        $this->em->flush();
    }
}
