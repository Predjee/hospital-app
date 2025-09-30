<?php

declare(strict_types=1);

namespace App\Admission\UI\Input;

use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Validator\Constraints as Assert;

final class PatientAdmissionInput
{
    #[Assert\NotBlank(message: 'Naam mag niet leeg zijn.')]
    public string $name {
        set => $this->name = trim($value);
    }

    #[Assert\NotBlank(message: 'Geboortedatum is verplicht.')]
    #[Context(['datetime_format' => 'Y-m-d'])]
    #[Assert\LessThanOrEqual('today', message: 'Geboortedatum mag niet in de toekomst liggen.')]
    public \DateTimeImmutable $birthDate {
        set => $value->setTime(0, 0);
    }
}
