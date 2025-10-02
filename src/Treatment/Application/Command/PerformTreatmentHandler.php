<?php

declare(strict_types=1);

namespace App\Treatment\Application\Command;

use App\Patient\Application\Query\PatientFinder;
use App\Treatment\Application\Result\TreatmentResult;
use App\Treatment\Application\Strategy\TreatmentResolver;
use App\Treatment\Domain\Event\TreatmentCompletedEvent;
use App\Treatment\Domain\Event\TreatmentStartedEvent;
use App\Treatment\Domain\Repository\TreatmentRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class PerformTreatmentHandler
{
    public function __construct(
        private TreatmentRepository $treatments,
        private TreatmentResolver $resolver,
        private EventDispatcherInterface $dispatcher,
        private PatientFinder $patientFinder,
    ) {
    }

    public function __invoke(PerformTreatmentCommand $command): TreatmentResult
    {
        $treatment = $this->treatments->get($command->treatmentId);

        $this->dispatcher->dispatch(
            new TreatmentStartedEvent(
                $treatment->id(),
                $treatment->patient()->id(),
                new \DateTimeImmutable()
            )
        );

        $message = $this->resolver->resolve($treatment->patient(), $treatment->type());

        $this->dispatcher->dispatch(
            new TreatmentCompletedEvent(
                $treatment->id(),
                $treatment->patient()->id(),
                new \DateTimeImmutable()
            )
        );

        $summary = $this->patientFinder->byId($treatment->patient()->id());

        return new TreatmentResult(
            message: $message,
            type: 'success',
            patientSummary: $summary,
        );
    }
}
