<?php

declare(strict_types=1);

namespace App\Tests\Patient\Infrastructure\EventListener;

use App\Audit\Application\Command\RecordActionCommand;
use App\Patient\Domain\Entity\Patient;
use App\Patient\Domain\Enum\PatientStatus;
use App\Patient\Domain\Event\PatientAdmittedEvent;
use App\Patient\Infrastructure\EventListener\PatientAdmittedListener;
use App\Treatment\Application\Command\AssignTreatmentCommand;
use App\Treatment\Domain\Enum\TreatmentType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class PatientAdmissionEventListenerTest extends TestCase
{
    public function testItDispatchesAssignTreatmentCommand(): void
    {
        $patient = new Patient('Test', new \DateTimeImmutable('2000-01-01'), PatientStatus::ADMITTED);

        $expectedCommand = new AssignTreatmentCommand($patient->id(), TreatmentType::MRI);

        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects(self::once())
            ->method('dispatch')
            ->with(
                self::callback(function ($command) use ($patient) {
                    return $command instanceof RecordActionCommand
                        && 'patient.admitted' === $command->action
                        && $command->payload['id']->equals($patient->id());
                })
            )
            ->willReturnCallback(fn ($command) => new Envelope($command));

        $listener = new PatientAdmittedListener($bus);
        $listener(new PatientAdmittedEvent($patient->id()));
    }
}
