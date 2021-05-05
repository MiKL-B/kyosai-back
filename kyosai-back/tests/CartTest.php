<?php

namespace App\Tests;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartTest extends WebTestCase
{
    public function testTestsAreWorking()
    {
        $client = static::createClient();
        $client->request('GET', '/shop');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(9, json_decode($response->getContent()));
    }
}
