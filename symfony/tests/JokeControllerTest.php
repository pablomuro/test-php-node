<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    public function setUp(): void{
        $this->client = static::createClient();
        $this->url = '/joke';
    }

    public function testJokeControllerGetMethod(): void
    {
        $this->client->request('GET', $this->url);
        
        $response = $this->client->getResponse();
        
        self::assertTrue($response->isSuccessful());
    }
}
