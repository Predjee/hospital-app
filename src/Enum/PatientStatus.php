<?php

declare(strict_types=1);

namespace App\Enum;

enum PatientStatus: string
{
    case ADMITTED = 'admitted';
    case DISCHARGED = 'discharged';
    case ICU = 'icu';

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMITTED => 'Opgenomen',
            self::DISCHARGED => 'Ontslagen',
            self::ICU => 'IC',
        };
    }

    public function getColorClass(): string
    {
        return match ($this) {
            self::ADMITTED => 'bg-green-200 text-green-800',
            self::DISCHARGED => 'bg-gray-200 text-gray-800',
            self::ICU => 'bg-red-200 text-red-800',
        };
    }
}
