<?php

declare(strict_types=1);

namespace App\Tests\Treatment;

use App\Entity\Patient;
use App\Entity\Treatment;
use App\Enum\PatientStatus;
use App\Enum\TreatmentType;
use App\Treatment\SurgeryTreatment;
use App\Treatment\TreatmentResolver;
use PHPUnit\Framework\TestCase;

final class TreatmentResolverTest extends TestCase
{
    public function testResolvesCorrectStrategy(): void
    {
        $patient = new Patient(name: 'Test', status: PatientStatus::ADMITTED);
        $treatment = new Treatment($patient, TreatmentType::SURGERY);
        $patient->addTreatment($treatment);

        $resolver = new TreatmentResolver([new SurgeryTreatment()], $this->createMock('Doctrine\ORM\EntityManagerInterface'));
        $message = $resolver->resolve($patient, TreatmentType::SURGERY);

        $this->assertStringContainsString('onder het mes', $message);
    }
}
