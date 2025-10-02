<?php

declare(strict_types=1);

namespace App\Treatment\Application\Command;

use App\Patient\Domain\Entity\Patient;
use App\Patient\Domain\Repository\PatientRepository;
use App\Treatment\Domain\Entity\Treatment;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AssignTreatmentHandler
{
    public function __construct(private PatientRepository $patients)
    {
    }

    public function __invoke(AssignTreatmentCommand $command): void
    {
        /** @var Patient $patient */
        $patient = $this->patients->find($command->patientId);

        $treatment = new Treatment($patient, $command->treatmentType);
        $patient->addTreatment($treatment);

        $this->patients->save($patient);
    }
}
