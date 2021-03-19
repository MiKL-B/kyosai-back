<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ApiShopController extends AbstractController
{
    /**
     * @Route("/api/shop", name="api_shop_index", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository): response
    {
        return $this->json($produitsRepository->findAll(), 200, []);
    }
}
