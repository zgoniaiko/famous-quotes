<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    public function testEmptyListOfQoutes()
    {
        $client = static::createClient();
        $client->request('GET', '/quotes');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertEquals('[]', $response->getContent());
    }

    public function testPostShouldCreateQoute()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/quotes',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode([
                'quote' => 'test quote',
                'author' => 'someone',
            ])
        );

        $this->assertSame(201, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertEquals('{"status":"ok"}', $response->getContent());
    }
}
