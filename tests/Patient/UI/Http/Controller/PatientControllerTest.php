<?php

declare(strict_types=1);

namespace App\Tests\Patient\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PatientControllerTest extends WebTestCase
{
    public function testIndexPageShowsPatients(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Patiëntsamenvattingen');
    }
}
