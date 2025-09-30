<?php

declare(strict_types=1);

namespace App\Patient\Application\Service;

use App\Patient\Application\DTO\PatientSummaryData;
use App\Patient\Domain\Entity\Patient;
use App\Patient\Infrastructure\Repository\PatientRepository;
use App\Treatment\Application\DTO\TreatmentSummaryData;
use App\Treatment\Domain\Entity\Treatment;

readonly class PatientSummaryFactory
{
    public function __construct(private PatientRepository $patientRepository)
    {
    }

    public function createFromEntity(Patient $patient): PatientSummaryData
    {
        $treatments = $patient->treatments()->map(
            fn (Treatment $treatment) => new TreatmentSummaryData(
                id: $treatment->id(),
                type: $treatment->type(),
                completed: $treatment->isCompleted(),
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

    /** @return PatientSummaryData[] */
    public function createCollection(bool $withTreatments = true): array
    {
        $patients = $withTreatments
            ? $this->patientRepository->findWithTreatments()
            : $this->patientRepository->findAll();

        return array_map([$this, 'createFromEntity'], $patients);
    }

    public function createFromId(int $id): ?PatientSummaryData
    {
        $patient = $this->patientRepository->find($id);

        return $patient ? $this->createFromEntity($patient) : null;
    }
}
