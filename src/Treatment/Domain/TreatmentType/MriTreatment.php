<?php

declare(strict_types=1);

namespace App\Treatment\Domain\TreatmentType;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Application\Strategy\TreatmentStrategy;
use App\Treatment\Domain\Enum\TreatmentType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.treatment_strategy')]
class MriTreatment implements TreatmentStrategy
{
    public function supports(Patient $patient, TreatmentType $type): bool
    {
        return TreatmentType::MRI === $type && $patient->hasPendingTreatment($type);
    }

    public function treat(Patient $patient): string
    {
        $treatment = $patient->findTreatmentByType(TreatmentType::MRI);

        if (!$treatment) {
            throw new \LogicException('Geen MRI behandeling gevonden.');
        }

        return "Patiënt {$patient->name()} krijgt een MRI-scan. "
            .'Belangrijkste regel: niet bewegen. Dus geen TikToks opnemen in de tunnel, hoe goed het ritme van het gebrom ook klinkt.';
    }
}
