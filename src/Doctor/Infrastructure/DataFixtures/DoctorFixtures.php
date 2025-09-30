<?php

declare(strict_types=1);

namespace App\Doctor\Infrastructure\DataFixtures;

use App\Department\Domain\Entity\Department;
use App\Doctor\Domain\Entity\Doctor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class DoctorFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('nl_NL');

        $departments = [
            new Department('Radiologie'),
            new Department('Chirurgie'),
            new Department('Fysiotherapie'),
            new Department('Revalidatie'),
        ];

        foreach ($departments as $department) {
            $manager->persist($department);
        }

        for ($i = 0; $i < 15; ++$i) {
            $doctor = new Doctor(
                name: 'Dr. '.$faker->lastName()
            );

            $assignedDepartments = $faker->randomElements($departments, $faker->numberBetween(1, 3));
            foreach ($assignedDepartments as $dep) {
                $doctor->assignTo($dep);
            }

            $manager->persist($doctor);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['doctors'];
    }
}
