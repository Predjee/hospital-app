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
            throw new \LogicException('No MRI treatment found.');
        }

        return "Patient {$patient->name()} is scheduled for an MRI scan. "
            .'Most important rule: do not move. So please, no TikToks inside the tunnel, '
            .'no matter how catchy the hum of the machine may sound.';
    }
}
