<?php

declare(strict_types=1);

namespace App\Treatment\Domain\TreatmentType;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Application\Strategy\TreatmentStrategy;
use App\Treatment\Domain\Enum\TreatmentType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.treatment_strategy')]
class SurgeryTreatment implements TreatmentStrategy
{
    public function supports(Patient $patient, TreatmentType $type): bool
    {
        return TreatmentType::SURGERY === $type && $patient->hasPendingTreatment($type);
    }

    public function treat(Patient $patient): string
    {
        $treatment = $patient->findTreatmentByType(TreatmentType::SURGERY);

        if (!$treatment) {
            throw new \LogicException('No surgery treatment found.');
        }

        return "Patient {$patient->name()} is heading into surgery. "
            .'Don’t worry: the team is ready, the lights are bright, '
            .'and the surgeon has already had their coffee. '
            .'Hopefully this will be the fastest pit stop since Formula 1.';
    }
}
