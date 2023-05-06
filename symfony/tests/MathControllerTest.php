<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MathControllerTest extends WebTestCase
{
    public function setUp(): void{
        $this->client = static::createClient();
        $this->url = '/math';
    }

    public function testRequestWithAValidNumber(): void
    {
        $validNumber = 1;
        $this->client->request('GET', "$this->url?number=$validNumber");
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        
        self::assertTrue($response->isSuccessful());
        self::assertEquals($content, 2);
    }

    public function testRequestWithAInvalidNumber(): void
    {
        $invalidNumber = 'a';
        $this->client->request('GET', "$this->url?number=$invalidNumber");
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        
        self::assertTrue($response->isSuccessful());
        self::assertEquals($content, 'Invalid query parameter number: Not a number');
    }

    public function testRequestWithAValidNumbersArray(): void
    {
        $validNumbersArray = '[2,7,3,9,4]';
        $this->client->request('GET', "$this->url?numbers=$validNumbersArray");
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        
        self::assertTrue($response->isSuccessful());
        self::assertEquals($content, 252);
    }

    public function testRequestWithAInvalidNumbersArray(): void
    {
        $invalidNumber = '[2,7,3,9,"a"]';
        $this->client->request('GET', "$this->url?numbers=$invalidNumber");
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        
        self::assertTrue($response->isSuccessful());
        self::assertEquals($content, 'Invalid query parameter numbers: Not a list of numbers');

        $invalidNumber = '3,9,"a"';
        $this->client->request('GET', "$this->url?numbers=$invalidNumber");
        
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent(), true);
        
        self::assertTrue($response->isSuccessful());
        self::assertEquals($content, 'Invalid query parameter numbers: Not a list');
    }
}
