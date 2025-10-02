<?php

declare(strict_types=1);

namespace App\Patient\Application\Command;

use App\Patient\Domain\Entity\Patient;
use App\Patient\Domain\Event\PatientAdmittedEvent;
use App\Patient\Infrastructure\Repository\DoctrinePatientRepository;
use App\Treatment\Domain\Entity\Treatment;
use App\Treatment\Domain\Enum\TreatmentType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class AdmitPatientHandler
{
    public function __construct(
        private DoctrinePatientRepository $patients,
        private EventDispatcherInterface $eventBus,
    ) {
    }

    public function __invoke(AdmitPatientCommand $command): Patient
    {
        $patient = new Patient($command->name, $command->birthDate);

        $this->patients->save($patient);
        $intakeTreatment = new Treatment($patient, TreatmentType::INTAKE);
        $patient->addTreatment($intakeTreatment);

        $this->eventBus->dispatch(new PatientAdmittedEvent($patient->id()));

        return $patient;
    }
}
