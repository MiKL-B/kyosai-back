<?php

namespace App\Controller;



use App\Entity\Category;
use App\Entity\Produits;
use App\Repository\CategoryRepository;
use App\Repository\ProduitsRepository;
use DateTime;
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
        $body = json_decode($request->getContent(), true);
        $produit->setNom($body["name_produit"]);
        $produit->setPrix($body["prix_produit"]);
        $produit->setImage($body["image_produit"]);
        $produit->setCreatedAt(new DateTime());
        $persistedCategory = $categoryRepository->findOneBy(['label' => $body["category_produit"]]);
        $produit->addCategory($persistedCategory);
        $manager->persist($produit);
        $manager->flush();

        return $this->json($produit);
    }
    /**
     * @Route("/api/admin/edit/{id}", name="api_admin_edit", methods={"POST","GET"})
     */
    public function edit(int $id, ProduitsRepository $produit, Request $request)
    {
        return $this->json($produit->find($id));
    }
}
