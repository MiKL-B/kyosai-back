<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiShopController extends AbstractController
{
    /**
     * @Route("/api/shop", name="api_shop_index", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository, CategoryRepository $categoryRepository): response
    {
        return $this->json($produitsRepository->findAll(), 200, []);
    }
}
