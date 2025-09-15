<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DataFixtures\PatientFixtures;
use App\Repository\PatientRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PatientRepositoryTest extends KernelTestCase
{
    private PatientRepository $repo;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repo = static::getContainer()->get(PatientRepository::class);

        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([PatientFixtures::class]);
    }

    public function testFindSummariesReturnsDtos(): void
    {
        $summaries = $this->repo->findSummaries();

        $this->assertNotEmpty($summaries);
        $this->assertIsString($summaries[0]->name);
        $this->assertGreaterThanOrEqual(0, $summaries[0]->age);
    }
}
