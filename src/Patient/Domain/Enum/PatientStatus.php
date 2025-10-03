<?php

declare(strict_types=1);

namespace App\Patient\Domain\Enum;

enum PatientStatus: string
{
    case ADMITTED = 'admitted';
    case DISCHARGED = 'discharged';

    public function label(): string
    {
        return match ($this) {
            self::ADMITTED => 'Admitted',
            self::DISCHARGED => 'Discharged',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::ADMITTED => 'bg-blue-100 text-blue-800',
            self::DISCHARGED => 'bg-green-100 text-green-800',
        };
    }
}
