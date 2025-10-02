<?php

declare(strict_types=1);

namespace App\Treatment\Application\Strategy;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Domain\Enum\TreatmentType;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class TreatmentResolver
{
    /**
     * @param iterable<TreatmentStrategy> $strategies
     */
    public function __construct(
        #[AutowireIterator('app.treatment_strategy')]
        public iterable $strategies,
    ) {
    }

    public function resolve(Patient $patient, TreatmentType $type): string
    {
        foreach ($this->strategies as $strategy) {
            if (!$strategy->supports($patient, $type)) {
                continue;
            }

            $message = $strategy->treat($patient);

            $patient->findTreatmentByType($type)->markAsCompleted();

            $patient->dischargeIfNoPendingTreatments();

            return $message;
        }

        throw new \LogicException("Dit had beter gechecked moeten worden, {$patient->name()} heeft geen behandelingen nodig!");
    }
}
