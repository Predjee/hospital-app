<?php

declare(strict_types=1);

namespace App\Treatment\Domain\TreatmentType;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Application\Strategy\TreatmentStrategy;
use App\Treatment\Domain\Enum\TreatmentType;
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
            throw new \LogicException('No physiotherapy treatment found.');
        }

        return "Patient {$patient->name()} has some serious work ahead with the physiotherapist. "
            .'That means squats, lunges and a bit of stretching—no excuses. '
            .'If it feels like you’ve just run a marathon, you’re on the right track.';
    }
}
