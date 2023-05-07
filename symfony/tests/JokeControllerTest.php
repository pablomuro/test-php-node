<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JokeControllerTest extends WebTestCase
{
    public function setUp(): void{
        $this->client = static::createClient();
        $this->url = '/joke';
    }

    public function testGetMethod(): void
    {
        $this->client->request('GET', $this->url);
        
        $response = $this->client->getResponse();
        
        self::assertTrue($response->isSuccessful());
    }

    public function testGetMethodWithValidParam(): void
    {
        $this->client->request('GET', "$this->url/Chuck");
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        
        self::assertTrue($response->isSuccessful());
        self::assertIsString($content);
        self::assertNotEmpty($content);

        $this->client->request('GET', "$this->url/Dad");
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        
        self::assertTrue($response->isSuccessful());
        self::assertIsString($content);
        self::assertNotEmpty($content);
    }

    public function testGetMethodWithInValidParam(): void
    {
        $this->client->request('GET', "$this->url/ChuckWrongParams");
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertFalse($response->isSuccessful());
        self::assertEquals($response->getStatusCode(), 400);
        self::assertIsString($content);
        self::assertEquals($content, "No Joke Found");
    }

    public function testCreateNewJoke(): void
    {
        $this->client->request('POST', $this->url, ['joke' => 'New Test Joke']);
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertTrue($response->isSuccessful());
        self::assertEquals($content['jokeText'], 'New Test Joke');
    }

    public function testCreateNewJokeWithInvalidParam(): void
    {
        $this->client->request('POST', $this->url, ['joke' => '']);
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertFalse($response->isSuccessful());
        self::assertEquals($response->getStatusCode(), 400);
        self::assertEquals($content, 'Invalid Joke Text');
    }

    public function testEditJoke(): void
    {
        $this->client->request('POST', $this->url, ['joke' => 'New Test Joke']);
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $id = $content['id'];

        $this->client->request('PUT', $this->url, ['number' => $id, 'joke' => 'Edited Test Joke']);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertTrue($response->isSuccessful());
        self::assertEquals($content['jokeText'], 'Edited Test Joke');
        self::assertEquals($content['id'], $id);
    }

    public function testEditJokeWithInvalidId(): void
    {
        $this->client->request('PUT', $this->url, ['number' => '']);
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertFalse($response->isSuccessful());
        self::assertEquals($response->getStatusCode(), 400);
        self::assertEquals($content, 'Not Found');

        $this->client->request('PUT', $this->url, ['number' => -1, 'joke' => 'Edited Test Joke']);
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertFalse($response->isSuccessful());
        self::assertEquals($response->getStatusCode(), 400);
        self::assertEquals($content, 'Not Found');

        $this->client->request('PUT', $this->url, ['number' => -1, 'joke' => '']);
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertFalse($response->isSuccessful());
        self::assertEquals($response->getStatusCode(), 400);
        self::assertEquals($content, 'Invalid Joke Text');
    }

     public function testDeleteJoke(): void
    {
        $this->client->request('POST', $this->url, ['joke' => 'New Test Joke']);
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        $id = $content['id'];

        $this->client->request('DELETE', $this->url, ['number' => $id]);

        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertTrue($response->isSuccessful());
        self::assertEquals($content, 'Joke Removed');
    }

    public function testDeleteJokeWithInvalidId(): void
    {
        $this->client->request('DELETE', $this->url, ['number' => -1]);
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);

        self::assertFalse($response->isSuccessful());
        self::assertEquals($response->getStatusCode(), 400);
        self::assertEquals($content, 'Not Found');
    }
}
