<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class QuoteControllerTest extends WebTestCase
{
    public function testUnauthorizedAccess()
    {
        $client = $this->getClient();
        $client->request('GET', '/quotes');
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());

        $this->createQuote($client, 'test quote', 'someone', Response::HTTP_UNAUTHORIZED);

        $client->request(
            'PUT',
            '/quotes/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode([
                'quote' => 'updated quote',
                'author' => 'someone',
            ])
        );
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());

        $client->request('DELETE', '/quotes/1');
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testEmptyListOfQoutes()
    {
        $client = $this->getClient(true);
        $client->request('GET', '/quotes');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertEquals('[]', $response->getContent());
    }

    public function testNonExistQuote()
    {
        $client = $this->getClient(true);
        $client->request('GET', '/quote/999');

        $this->assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testPostShouldCreateQoute()
    {
        $client = $this->getClient(true);
        $this->createQuote($client, 'test quote', 'someone');

        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $this->assertEquals('{"status":"ok"}', $response->getContent());
    }

    public function testPutShouldUpdateQoute()
    {
        $client = $this->getClient(true);
        $this->createQuote($client, 'test quote', 'someone');

        $client->request(
            'PUT',
            '/quotes/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode([
                'quote' => 'updated quote',
                'author' => 'someone',
            ])
        );

        $this->assertSame(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }

    public function testPutShouldCreateQoute()
    {
        $client = $this->getClient(true);

        $client->request(
            'PUT',
            '/quotes/3',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode([
                'quote' => 'created quote',
                'author' => 'someone',
            ])
        );

        $this->assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testDeleteQuote()
    {
        $client = $this->getClient(true);

        $client->request('DELETE', '/quotes/1');
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    protected function createQuote(Client $client, $quote, $author, $status = Response::HTTP_CREATED)
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

        $this->assertSame($status, $client->getResponse()->getStatusCode());
    }

    private function getClient($authenticated = false): Client
    {
        $params = [];
        if ($authenticated) {
            $params = array_merge($params, [
                'PHP_AUTH_USER' => 'api',
                'PHP_AUTH_PW'   => 'pass',
            ]);
        }

        return static::createClient([], $params);
    }
}
