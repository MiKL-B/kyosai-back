<?php

namespace App\Tests;

use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartTest extends WebTestCase
{

    public function testTestsAreWorking()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UsersRepository::class);
        //récupérer l'utilisateur de test
        $testUser = $userRepository->findOneByEmail('admin@kyosai.fr');
        // simuler qu'un utilisateur de test est connecté 
        $client->loginUser($testUser);
        // dd($testUser);
        //login page
        $client->request('GET', '/api/login_check');
        $this->assertResponseIsSuccessful();
        // $client->request('GET', '/shop');
        // $response = $client->getResponse();
        // $this->assertEquals(200, $response->getStatusCode());
        // $this->assertCount(9, json_decode($response->getContent()));
    }
}
//php bin/phpunit tests/CartTest
