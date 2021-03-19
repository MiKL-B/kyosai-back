<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ApiShopController extends AbstractController
{
    /**
     * @Route("/api/shop", name="api_shop_index", methods={"GET"})
     */
    public function index(): response
    {

        $json = json_encode(
            "text",
        );


        return  $this->json($json, 200, []);
    }
}
