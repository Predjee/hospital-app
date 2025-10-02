<?php

declare(strict_types=1);

namespace App\Tests\Patient\Infrastructure\Repository;

use App\Patient\Domain\Entity\Patient;
use App\Patient\Domain\Enum\PatientStatus;
use App\Patient\Infrastructure\DataFixtures\PatientFixtures;
use App\Patient\Infrastructure\Repository\DoctrinePatientRepository;
use App\Treatment\Domain\Entity\Treatment;
use App\Treatment\Domain\Enum\TreatmentType;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PatientRepositoryTest extends KernelTestCase
{
    private DoctrinePatientRepository $repo;
    private AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = PatientRepositoryTest::getContainer();
        $this->repo = $container->get(DoctrinePatientRepository::class);

        $this->databaseTool = $container->get(DatabaseToolCollection::class)->get();

        $this->databaseTool->loadFixtures([PatientFixtures::class]);
    }

    public function testFindWithTreatmentsReturnsPatientsWithTreatments(): void
    {
        $patients = $this->repo->findWithTreatments();

        self::assertNotEmpty($patients);
        self::assertInstanceOf(Patient::class, $patients[0]);

        $patient = $patients[0];
        self::assertIsIterable($patient->treatments());
    }

    public function testSaveAndRemovePatient(): void
    {
        $patient = new Patient('Jan', new \DateTimeImmutable('1990-01-01'), PatientStatus::ADMITTED);

        $this->repo->save($patient, true);
        $found = $this->repo->find($patient->id());

        self::assertNotNull($found);
        self::assertSame('Jan', $found->name());

        $this->repo->remove($patient, true);
        $deleted = $this->repo->find($patient->id());

        self::assertNull($deleted);
    }

    public function testHasPendingTreatmentLogic(): void
    {
        $patient = new Patient('Piet', new \DateTimeImmutable('1985-01-01'), PatientStatus::ADMITTED);
        $treatment = new Treatment($patient, TreatmentType::MRI);
        $patient->addTreatment($treatment);

        self::assertTrue($patient->hasPendingTreatment(TreatmentType::MRI));

        $treatment->complete();

        self::assertFalse($patient->hasPendingTreatment(TreatmentType::MRI));
    }
}
