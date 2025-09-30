<?php

declare(strict_types=1);

namespace App\Tests\Patient\Infrastructure\EventListener;

use App\Patient\Domain\Entity\Patient;
use App\Patient\Domain\Enum\PatientStatus;
use App\Patient\Domain\Event\PatientAdmittedEvent;
use App\Patient\Infrastructure\EventListener\PatientAdmittedListener;
use App\Treatment\Domain\Enum\TreatmentType;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PatientAdmissionEventListenerTest extends KernelTestCase
{
    private AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = static::getContainer()
            ->get(DatabaseToolCollection::class)
            ->get();
    }

    public function testSubscriberPersistsPatientWithTreatment(): void
    {
        $this->databaseTool->loadFixtures([]);

        $patient = new Patient('Test', new \DateTimeImmutable('2000-01-01'), PatientStatus::ADMITTED);

        $em = static::getContainer()->get(EntityManagerInterface::class);

        $listener = new PatientAdmittedListener($em);
        $listener(new PatientAdmittedEvent($patient));

        $em->clear();

        $saved = $em->getRepository(Patient::class)->find($patient->id());

        self::assertNotNull($saved);
        self::assertTrue($saved->hasPendingTreatment(TreatmentType::MRI));
    }
}
