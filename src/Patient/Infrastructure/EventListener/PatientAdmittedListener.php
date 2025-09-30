<?php

declare(strict_types=1);

namespace App\Patient\Infrastructure\EventListener;

use App\Patient\Domain\Event\PatientAdmittedEvent;
use App\Treatment\Domain\Entity\Treatment;
use App\Treatment\Domain\Enum\TreatmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final readonly class PatientAdmittedListener
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(PatientAdmittedEvent $event): void
    {
        $patient = $event->patient;

        $treatment = new Treatment($patient, TreatmentType::MRI);
        $patient->addTreatment($treatment);

        $this->em->persist($patient);
        $this->em->flush();
    }
}
