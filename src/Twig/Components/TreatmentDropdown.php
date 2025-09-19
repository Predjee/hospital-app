<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Enum\TreatmentType;
use App\Repository\PatientRepository;
use App\Treatment\TreatmentResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('TreatmentDropdown')]
final class TreatmentDropdown
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $patientId;

    public ?string $message = null;

    public function __construct(
        private readonly TreatmentResolver $resolver,
        private readonly PatientRepository $patients,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
     * @return TreatmentType[]
     */
    public function getAvailableTreatments(): array
    {
        $patient = $this->patients->find($this->patientId);

        return $patient
            ? array_map(fn ($t) => $t->type, $patient->getPendingTreatments())
            : [];
    }

    #[LiveAction]
    public function treat(#[LiveArg] TreatmentType $treatment): void
    {
        $patient = $this->patients->find($this->patientId);
        if (!$patient) {
            throw new \RuntimeException('There is a zombie on the loose!');
        }

        $this->message = $this->resolver->resolve($patient, $treatment);
    }
}
