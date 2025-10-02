<?php

declare(strict_types=1);

namespace App\Patient\Application\Query;

use App\Patient\Application\DTO\PatientSummaryData;
use App\Patient\Domain\Entity\Patient;
use App\Patient\Domain\Repository\PatientRepository;
use App\Treatment\Application\DTO\TreatmentSummaryData;
use App\Treatment\Domain\Entity\Treatment;
use Symfony\Component\Uid\Ulid;

final readonly class PatientFinder
{
    public function __construct(private PatientRepository $repo)
    {
    }

    /** @return PatientSummaryData[] */
    public function all(bool $withTreatments = true): array
    {
        $patients = $withTreatments
            ? $this->repo->findWithTreatments()
            : $this->repo->findAll();

        return array_map([$this, 'map'], $patients);
    }

    public function byId(Ulid $id): ?PatientSummaryData
    {
        $patient = $this->repo->find($id);

        return $patient ? $this->map($patient) : null;
    }

    private function map(Patient $patient): PatientSummaryData
    {
        $treatments = $patient->treatments()->map(
            fn (Treatment $t) => new TreatmentSummaryData(
                id: $t->id(),
                type: $t->type(),
                completed: $t->isCompleted(),
            )
        )->toArray();

        return new PatientSummaryData(
            id: $patient->id(),
            name: $patient->name(),
            status: $patient->status(),
            age: $patient->age(),
            treatments: $treatments,
        );
    }
}
