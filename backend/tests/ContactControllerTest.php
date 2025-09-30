<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContactEndpointReturnsOk(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/contact', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Teszt Elek',
            'email' => 'teszt@gmailvagyvalami.com',
            'message' => 'Ez egy teszt üzenet.',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());
    }
}
