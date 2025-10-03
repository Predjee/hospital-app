<?php

declare(strict_types=1);

namespace App\Admission\UI\Input;

use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Validator\Constraints as Assert;

final class PatientAdmissionInput
{
    #[Assert\NotBlank(message: 'Name cannot be blank.')]
    public string $name {
        set => $this->name = trim($value);
    }

    #[Assert\NotBlank(message: 'Birth date is required.')]
    #[Context(['datetime_format' => 'Y-m-d'])]
    #[Assert\LessThanOrEqual('today', message: 'Birth date cannot be in the future.')]
    public \DateTimeImmutable $birthDate {
        set => $value->setTime(0, 0);
    }
}
