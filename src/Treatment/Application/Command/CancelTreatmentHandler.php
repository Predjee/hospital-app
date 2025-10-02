<?php

declare(strict_types=1);

namespace App\Treatment\Application\Command;

use App\Treatment\Domain\Event\TreatmentCancelledEvent;
use App\Treatment\Domain\Repository\TreatmentRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
final readonly class CancelTreatmentHandler
{
    public function __construct(
        private TreatmentRepository $repo,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(CancelTreatmentCommand $cmd): void
    {
        $treatment = $this->repo->get($cmd->treatmentId);
        $treatment->cancel();
        $this->repo->save($treatment);

        $this->dispatcher->dispatch(new TreatmentCancelledEvent(
            $treatment->id(),
            $treatment->patient()->id(),
        ));
    }
}
