<?php

declare(strict_types=1);

namespace App\Patient\Infrastructure\EventListener;

use App\Audit\Application\Command\RecordActionCommand;
use App\Patient\Domain\Event\PatientAdmittedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener]
final readonly class PatientAdmittedListener
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(PatientAdmittedEvent $event): void
    {
        $this->commandBus->dispatch(
            new RecordActionCommand('patient.admitted', [
                'id' => $event->patientId,
            ])
        );
    }
}
