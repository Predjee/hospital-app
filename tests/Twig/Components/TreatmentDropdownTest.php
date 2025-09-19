<?php

declare(strict_types=1);

namespace App\Tests\Twig\Components;

use App\Entity\Patient;
use App\Entity\Treatment;
use App\Enum\TreatmentType;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;

class TreatmentDropdownTest extends KernelTestCase
{
    use InteractsWithLiveComponents;

    private PatientRepository $patients;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->patients = self::getContainer()->get(PatientRepository::class);
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->entityManager->rollback();
    }

    public function testDropdownShowsOnlyPendingTreatments(): void
    {
        // 1. Patiënt opslaan
        $patient = new Patient('Test Patient');
        $this->entityManager->persist($patient);
        $this->entityManager->flush();

        // 2. Treatments toevoegen
        $pending = new Treatment($patient, TreatmentType::MRI, false);
        $completed = new Treatment($patient, TreatmentType::PHYSIOTHERAPY, true);
        $this->entityManager->persist($pending);
        $this->entityManager->persist($completed);
        $this->entityManager->flush();

        // 3. Entity manager clearen
        $this->entityManager->clear();

        // 4. Patient opnieuw ophalen zodat we een geldige ID hebben
        $patient = $this->patients->findOneBy(['name' => 'Test Patient']);
        self::assertNotNull($patient);

        // 5. Component aanmaken
        $component = $this->createLiveComponent('TreatmentDropdown', [
            'patientId' => $patient->getId(),
        ]);

        // 6. Renderen en asserten
        $html = $component->render()->toString();

        $this->assertStringContainsString('MRI-scan', $html);
        $this->assertStringNotContainsString('Fysiotherapie', $html);
        $this->assertStringNotContainsString('Geen behandelingen', $html);
    }
}
