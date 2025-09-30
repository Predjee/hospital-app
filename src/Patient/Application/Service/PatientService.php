<?php

declare(strict_types=1);

namespace App\Patient\Application\Service;

use App\Admission\UI\Input\PatientAdmissionInput;
use App\Patient\Domain\Entity\Patient;
use App\Patient\Domain\Event\PatientAdmittedEvent;
use App\Patient\Infrastructure\Repository\PatientRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class PatientService
{
    public function __construct(
        private PatientRepository $patients,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function admit(PatientAdmissionInput $intake): Patient
    {
        $patient = new Patient(
            name: $intake->name,
            birthDate: $intake->birthDate,
        );

        $this->patients->save($patient, true);
        $this->dispatcher->dispatch(new PatientAdmittedEvent($patient));

        return $patient;
    }
}
