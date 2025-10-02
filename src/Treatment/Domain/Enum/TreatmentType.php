<?php

declare(strict_types=1);

namespace App\Treatment\Domain\Enum;

enum TreatmentType: string
{
    case SURGERY = 'surgery';
    case REHAB = 'rehab';
    case MRI = 'mri';
    case PHYSIOTHERAPY = 'physiotherapy';
    case INTAKE = 'intake';

    public function getLabel(): string
    {
        return match ($this) {
            self::SURGERY => 'Operatie',
            self::MRI => 'MRI-scan',
            self::PHYSIOTHERAPY => 'Fysiotherapie',
            self::REHAB => 'Revalidatie',
            self::INTAKE => 'Intake',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            TreatmentType::SURGERY => 'bg-red-100 text-red-800',
            TreatmentType::MRI => 'bg-blue-100 text-blue-800',
            TreatmentType::PHYSIOTHERAPY => 'bg-green-100 text-green-800',
            TreatmentType::REHAB => 'bg-yellow-100 text-yellow-800',
            TreatmentType::INTAKE => 'bg-green-100 text-black-800',
        };
    }
}
