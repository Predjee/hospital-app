<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Enum\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class DoctorFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('nl_NL');

        foreach (Department::cases() as $department) {
            $doctor = new Doctor()
                ->setName($faker->name())
                ->setDepartment($department);

            $manager->persist($doctor);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['doctors'];
    }
}
