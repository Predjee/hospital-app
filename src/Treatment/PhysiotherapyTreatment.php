<?php

declare(strict_types=1);

namespace App\Treatment;

use App\Entity\Patient;
use App\Enum\TreatmentType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.treatment_strategy')]
class PhysiotherapyTreatment implements TreatmentStrategy
{
    public function supports(Patient $patient, TreatmentType $type): bool
    {
        return TreatmentType::PHYSIOTHERAPY === $type && $patient->hasPendingTreatment($type);
    }

    public function treat(Patient $patient): string
    {
        $treatment = $patient->findTreatmentByType(TreatmentType::PHYSIOTHERAPY);

        if (!$treatment) {
            throw new \LogicException('Geen fysio behandeling gevonden.');
        }

        return "Patiënt {$patient->name} moet flink aan de bak bij de fysiotherapeut. "
            .'Dat betekent: squats, lunges en een beetje rekken—geen excuses. '
            .'Als het voelt alsof je een marathon hebt gelopen, dan zit je goed.';
    }
}
