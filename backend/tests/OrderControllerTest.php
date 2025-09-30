<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\MailerInterface;

class OrderControllerTest extends WebTestCase
{
    public function testOrderEndpoint(): void
    {
        $client = static::createClient();

        // Mock mailer létrehozása
        $mockMailer = $this->createMock(MailerInterface::class);
        $mockMailer->expects($this->once())->method('send');

        // Felülírjuk a mailer szolgáltatást a mockkal
        $client->getContainer()->set('mailer.mailer', $mockMailer);

        // Ezután mehet a kérés, ami már nem küld valódi levelet
        $client->request('POST', '/api/order', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Teszt',
            'zip' => '1111',
            'city' => 'Budapest',
            'address' => 'Teszt utca 1',
            'phone' => '123456789',
            'email' => 'teszt@pelda.hu',
            'items' => [],
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }
}
