<?php

declare(strict_types=1);

namespace App\Tests\Treatment\Application\Strategy;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Application\Strategy\TreatmentResolver;
use App\Treatment\Domain\Entity\Treatment;
use App\Treatment\Domain\Enum\TreatmentType;
use App\Treatment\Domain\TreatmentType\SurgeryTreatment;
use PHPUnit\Framework\TestCase;

final class TreatmentResolverTest extends TestCase
{
    public function testResolvesCorrectStrategy(): void
    {
        $patient = new Patient(name: 'Test', birthDate: new \DateTimeImmutable('1990-01-01'));
        $treatment = new Treatment($patient, TreatmentType::SURGERY);
        $patient->addTreatment($treatment);

        $resolver = new TreatmentResolver([new SurgeryTreatment()]);
        $message = $resolver->resolve($patient, TreatmentType::SURGERY);

        $this->assertStringContainsString(' Don’t worry: the team is ready, the lights are bright, and the surgeon has already had their coffee.', $message);
    }

    public function testThrowsWhenNoStrategyFound(): void
    {
        $patient = new Patient(name: 'Test', birthDate: new \DateTimeImmutable('1990-01-01'));
        $treatment = new Treatment($patient, TreatmentType::MRI);

        $resolver = new TreatmentResolver([]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("This should have been validated earlier: {$patient->name()} does not require any treatments!");

        $resolver->resolve($patient, TreatmentType::MRI);
    }
}
