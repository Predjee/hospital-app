<?php

declare(strict_types=1);

namespace App\Treatment;

use App\Entity\Patient;
use App\Enum\TreatmentType;
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
            throw new \LogicException('Geen revalidatie behandeling gevonden.');
        }

        return "Patiënt {$patient->name} is doorgestuurd naar de revalidatiezaal. "
            ."En nee, dit is niet de Amy Winehouse-variant van rehab, hier moet je wél 'ja, ja, ja' zeggen tegen oefeningen.";
    }
}
