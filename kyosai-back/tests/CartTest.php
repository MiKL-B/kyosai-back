<?php

namespace App\Tests;

use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartTest extends WebTestCase
{

    public function testLoginUser()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UsersRepository::class);
        //récupérer l'utilisateur de test
        $testUser = $userRepository->findOneByEmail('admin@kyosai.fr');
        // simuler qu'un utilisateur de test est connecté 
        $client->loginUser($testUser);
        //page connexion
        $client->request('GET', '/api/login_check');
        $this->assertResponseIsSuccessful();

    }
}

//php bin/phpunit tests/CartTest
