<?php

declare(strict_types=1);

namespace App\Treatment;

use App\Entity\Patient;
use App\Enum\TreatmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class TreatmentResolver
{
    /**
     * @param iterable<TreatmentStrategy> $strategies
     */
    public function __construct(
        #[AutowireIterator('app.treatment_strategy')]
        public iterable $strategies,
        private EntityManagerInterface $em,
    ) {
    }

    public function resolve(Patient $patient, TreatmentType $type): string
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($patient, $type)) {
                $message = $strategy->treat($patient);

                // quick & dirty: behandel af en check ontslagregel
                if (empty($patient->getPendingTreatments())) {
                    $patient->discharge();
                }

                $this->em->flush();

                return $message;
            }
        }

        throw new \LogicException("Geen strategie voor {$type->name}");
    }
}
