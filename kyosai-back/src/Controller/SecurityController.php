<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Json;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login_check", name="api_login", methods={"POST","GET"})
     */
    public function api_login(): JsonResponse
    {
        $user = new json([
            "name" => "paul",
          
        ]);
        $user = $this->getUser();
        dd($user);
        return $this->json(array(
            'name' => $user->getName(),
         
        ));
    }
}
