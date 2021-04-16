<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class ApiCategoryListController extends AbstractController
{

    /**
     * @Route("/category/list", name="category_list", methods={"GET"})
     */
    public function categoryList(CategoryRepository $categoryRepository): response
    {
        return $this->json($categoryRepository->findAll(), 200, []);
    }
}
