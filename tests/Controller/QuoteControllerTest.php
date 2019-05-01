<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class QuoteControllerTest extends WebTestCase
{
    public function testEmptyListOfQoutes()
    {
        $client = static::createClient();
        $client->request('GET', '/quotes');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertEquals('[]', $response->getContent());
    }

    public function testPostShouldCreateQoute()
    {
        $client = static::createClient();
        $this->createQuote($client, 'test quote', 'someone');

        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertEquals('{"status":"ok"}', $response->getContent());
    }

    public function testDeleteQuote()
    {
        $client = static::createClient();

        $client->request('DELETE', '/quotes/1');
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    protected function createQuote(Client $client, $quote, $author)
    {
        $client->request(
            'POST',
            '/quotes',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode([
                'quote' => $quote,
                'author' => $author,
            ])
        );

        $this->assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}
