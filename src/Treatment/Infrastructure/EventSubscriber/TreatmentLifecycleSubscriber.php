<?php

declare(strict_types=1);

namespace App\Treatment\Infrastructure\EventSubscriber;

use App\Audit\Application\Command\RecordActionCommand;
use App\Treatment\Domain\Event\TreatmentCancelledEvent;
use App\Treatment\Domain\Event\TreatmentCompletedEvent;
use App\Treatment\Domain\Event\TreatmentStartedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class TreatmentLifecycleSubscriber implements EventSubscriberInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TreatmentStartedEvent::class => 'onTreatmentStarted',
            TreatmentCompletedEvent::class => 'onTreatmentCompleted',
            TreatmentCancelledEvent::class => 'onTreatmentCancelled',
        ];
    }

    public function onTreatmentStarted(TreatmentStartedEvent $event): void
    {
        $this->bus->dispatch(new RecordActionCommand(
            'treatment.started',
            ['id' => $event->treatmentId, 'patientId' => $event->patientId]
        ));
    }

    public function onTreatmentCompleted(TreatmentCompletedEvent $event): void
    {
        $this->bus->dispatch(new RecordActionCommand(
            'treatment.completed',
            ['id' => $event->treatmentId, 'patientId' => $event->patientId]
        ));
    }

    public function onTreatmentCancelled(TreatmentCancelledEvent $event): void
    {
        $this->bus->dispatch(new RecordActionCommand(
            'treatment.cancelled',
            ['id' => $event->treatmentId, 'patientId' => $event->patientId]
        ));
    }
}
