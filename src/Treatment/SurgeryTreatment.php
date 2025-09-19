<?php

declare(strict_types=1);

namespace App\Treatment;

use App\Entity\Patient;
use App\Enum\TreatmentType;
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
            throw new \LogicException('Geen chirurgie behandeling gevonden.');
        }

        return "Patiënt {$patient->name} gaat onder het mes. "
            .'Geen zorgen: het team staat klaar, de lampen zijn fel, '
            .'en de chirurg heeft z’n koffie al gehad. '
            .'Hopelijk wordt dit de snelste pitstop sinds Formule 1.';
    }
}
