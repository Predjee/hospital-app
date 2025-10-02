<?php

declare(strict_types=1);

namespace App\Treatment\Application\Command;

use App\Treatment\Domain\Event\TreatmentCompletedEvent;
use App\Treatment\Domain\Repository\TreatmentRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class CompleteTreatmentHandler
{
    public function __construct(
        private TreatmentRepository $repo,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(CompleteTreatmentCommand $cmd): void
    {
        $treatment = $this->repo->get($cmd->treatmentId);
        $treatment->complete();
        $this->repo->save($treatment);

        $this->dispatcher->dispatch(new TreatmentCompletedEvent(
            $treatment->id(),
            $treatment->patient()->id(),
            new \DateTimeImmutable(),
        ));
    }
}
