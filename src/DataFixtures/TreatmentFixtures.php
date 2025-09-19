<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Entity\Treatment;
use App\Enum\PatientStatus;
use App\Enum\TreatmentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class TreatmentFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('nl_NL');
        $patients = $manager->getRepository(Patient::class)->findAll();

        foreach ($patients as $patient) {
            if (PatientStatus::DISCHARGED === $patient->status) {
                continue;
            }

            $assignedTreatments = $faker->randomElements(TreatmentType::cases(), rand(1, 3));

            foreach ($assignedTreatments as $treatmentType) {
                $treatment = new Treatment(
                    patient: $patient,
                    type: $treatmentType,
                    completed: $faker->boolean(30)
                );

                $manager->persist($treatment);
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['patients', 'treatments'];
    }

    public function getDependencies(): array
    {
        return [PatientFixtures::class];
    }
}
