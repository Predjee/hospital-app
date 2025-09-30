<?php

declare(strict_types=1);

namespace App\Treatment\UI\Input;

use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

final class TreatmentActionInput
{
    #[Assert\NotBlank(message: 'Een behandeling-id is verplicht.')]
    #[Assert\Ulid(message: 'Dit is geen geldige behandeling-id.')]
    public string $treatmentId {
        set => $this->treatmentId = (string) Ulid::fromString($value);
    }
}
