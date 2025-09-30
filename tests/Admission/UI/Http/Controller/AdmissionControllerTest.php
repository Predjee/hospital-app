<?php

declare(strict_types=1);

namespace App\Tests\Admission\UI\Http\Controller;

use App\Patient\Infrastructure\DataFixtures\PatientFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AdmissionControllerTest extends WebTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = AdmissionControllerTest::createClient();

        $this->databaseTool = AdmissionControllerTest::getContainer()
            ->get(DatabaseToolCollection::class)
            ->get();
    }

    public function testPatientAdmissionSuccess(): void
    {
        $this->databaseTool->loadFixtures([]);

        $this->client->request('POST', '/admit', [
            'name' => 'Test Patient',
            'birthDate' => '2000-01-01',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'text/vnd.turbo-stream.html; charset=UTF-8');

        $content = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('Nieuwe patiënt', $content);
    }

    public function testPatientAdmissionFailsWithInvalidDate(): void
    {
        $this->databaseTool->loadFixtures([PatientFixtures::class]);

        $this->client->request('POST', '/admit', [
            'name' => 'Test Patient',
            'birthDate' => 'invalid-date',
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringNotContainsString('Nieuwe patiënt', $this->client->getResponse()->getContent());
    }
}
