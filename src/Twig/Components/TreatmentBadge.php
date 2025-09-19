<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\DTO\TreatmentSummary;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('TreatmentBadge')]
final class TreatmentBadge
{
    public TreatmentSummary $treatment;

    public function getColorClass(): string
    {
        return $this->treatment->type->getColor();
    }

    public function getLabel(): string
    {
        return $this->treatment->type->getLabel();
    }
}
