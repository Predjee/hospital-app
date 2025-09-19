<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\PatientSummary;
use App\DTO\TreatmentSummary;
use App\Entity\Patient;
use App\Enum\TreatmentType;
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

    /**
     * @return PatientSummary[]
     */
    public function findSummaries(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.status, p.birthDate, t.type AS treatmentType, t.completed AS treatmentCompleted')
            ->leftJoin('p.treatments', 't')
            ->orderBy('p.id', 'ASC');

        $results = $qb->getQuery()->getArrayResult();

        $grouped = [];
        foreach ($results as $row) {
            $id = $row['id'];

            if (!isset($grouped[$id])) {
                $grouped[$id] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'status' => $row['status'],
                    'birthDate' => $row['birthDate'],
                    'treatments' => [],
                ];
            }

            if ($row['treatmentType']) {
                $grouped[$id]['treatments'][] = new TreatmentSummary(
                    $row['treatmentType'],
                    (bool) $row['treatmentCompleted']
                );
            }
        }

        return array_map(
            fn ($data) => new PatientSummary(
                id: $data['id'],
                name: $data['name'],
                status: $data['status'],
                birthDate: $data['birthDate'],
                treatments: $data['treatments'],
            ),
            array_values($grouped)
        );
    }

    public function add(Patient $patient, bool $flush = true): void
    {
        $this->getEntityManager()->persist($patient);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Patient $patient, bool $flush = true): void
    {
        $this->getEntityManager()->remove($patient);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
