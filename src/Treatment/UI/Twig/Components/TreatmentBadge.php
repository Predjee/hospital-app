<?php

declare(strict_types=1);

namespace App\Treatment\UI\Twig\Components;

use App\Treatment\Application\DTO\TreatmentSummaryData;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('TreatmentBadge')]
final class TreatmentBadge
{
    public TreatmentSummaryData $treatment;

    public function getColorClass(): string
    {
        return $this->treatment->type->getColor();
    }

    public function getLabel(): string
    {
        return $this->treatment->type->getLabel();
    }
}
