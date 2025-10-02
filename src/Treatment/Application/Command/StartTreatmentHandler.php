<?php

declare(strict_types=1);

namespace App\Treatment\Application\Command;

use App\Treatment\Domain\Event\TreatmentStartedEvent;
use App\Treatment\Domain\Repository\TreatmentRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class StartTreatmentHandler
{
    public function __construct(
        private TreatmentRepository $repo,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(StartTreatmentCommand $cmd): void
    {
        $treatment = $this->repo->get($cmd->treatmentId);
        $treatment->start();
        $this->repo->save($treatment);

        $this->dispatcher->dispatch(new TreatmentStartedEvent(
            $treatment->id(),
            $treatment->patient()->id(),
            new \DateTimeImmutable(),
        ));
    }
}
