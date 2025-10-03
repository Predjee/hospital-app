<?php

declare(strict_types=1);

namespace App\Treatment\Domain\TreatmentType;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Application\Strategy\TreatmentStrategy;
use App\Treatment\Domain\Enum\TreatmentType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.treatment_strategy')]
class RehabTreatment implements TreatmentStrategy
{
    public function supports(Patient $patient, TreatmentType $type): bool
    {
        return TreatmentType::REHAB === $type && $patient->hasPendingTreatment($type);
    }

    public function treat(Patient $patient): string
    {
        $treatment = $patient->findTreatmentByType(TreatmentType::REHAB);

        if (!$treatment) {
            throw new \LogicException('No rehabilitation treatment found.');
        }

        return "Patient {$patient->name()} has been sent to the rehab ward. "
            ."And no, not the Amy Winehouse version of rehab—here you *do* say 'yes, yes, yes' to the exercises.";
    }
}
