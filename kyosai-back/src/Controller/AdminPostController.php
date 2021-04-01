<?php

namespace App\Controller;



use App\Entity\Category;
use App\Entity\Produits;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPostController extends AbstractController
{
    /**
     * @Route("/api/admin", name="api_admin_index", methods={"POST","GET"})
     */
    public function index(Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository): Response
    {
        $produit = new Produits();
        $category = new Category();
        $body = json_decode($request->getContent(), true);
        $produit->setNom($body["name_produit"]);
        $produit->setPrix($body["prix_produit"]);
        $produit->setImage($body["image_produit"]);
        // $produit->setCreatedAt($body["date_produit"]);
        // $category->setLabel($body["category_produit"]);

        //$manager->persist($category);
        // $persistedCategory = $categoryRepository->findOneByLabel($body["category_produit"]);
        // $produit->addCategory($persistedCategory);
        $manager->persist($produit);
        $manager->flush();

        return $this->json($produit);
    }
}
