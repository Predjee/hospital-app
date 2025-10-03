<?php

declare(strict_types=1);

namespace App\Treatment\UI\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class TreatmentActionInput
{
    #[Assert\NotBlank(message: 'A treatment is required.')]
    public string $treatmentId {
        set => $this->treatmentId = trim($value);
    }
}
