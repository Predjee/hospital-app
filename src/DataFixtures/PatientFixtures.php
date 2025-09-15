<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Enum\PatientStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class PatientFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('nl_NL');

        for ($i = 0; $i < 25; ++$i) {
            $birthDate = $faker->dateTimeBetween('now', '100 years');
            $patient = new Patient(
                name: $faker->name(),
                status: $faker->randomElement(PatientStatus::cases()),
                birthDate: \DateTimeImmutable::createFromMutable($birthDate)
            );

            if ($faker->boolean(20)) {
                $patient->discharge();
            }

            $manager->persist($patient);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['patients'];
    }
}
