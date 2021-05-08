<?php

namespace App\Controller;

use DateTime;
use App\Entity\Produits;
use App\Repository\CategoryRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Manages the operations to be performed on a product from the administration page
 * @author Michael BECQUER
 */
class AdminEditPostController extends AbstractController
{
    /**
     * @Route("/api/admin", name="api_admin_index", methods={"POST"})
     */
    /**
     * Add a product to the administration page
     *
     * @param  mixed $request
     * @param  mixed $manager
     * @param  mixed $categoryRepository
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository): Response
    {
        $produit = new Produits();
        $body = json_decode($request->getContent(), true);
        $produit->setNom($body["name_produit"]);
        $produit->setPrix($body["prix_produit"]);
        $produit->setImage($body["image_produit"]);
        $produit->setCreatedAt(new DateTime());
        foreach ($body['category_produit'] as $categoryLabel) {
            $persistedCategory = $categoryRepository->findOneBy(['label' => $categoryLabel]);
            $produit->addCategory($persistedCategory);
        }

        $manager->persist($produit);
        $manager->flush();

        return $this->json($produit);
    }
    /**
     * @Route("/api/admin/edit/view/{id}", name="api_admin_edit_view", methods={"GET"})
     */
    /**
     * Display the product 
     *
     * @param  integer $id
     * @param  mixed $produit
     * @return void
     */
    public function getEditContent(int $id,  ProduitsRepository $produit)
    {

        return $this->json($produit->find($id));
    }
    /**
     * @Route("/api/admin/edit/{id}", name="api_admin_edit", methods={"POST"})
     */
    /**
     * Modify the product
     *
     * @param  integer $id
     * @param  mixed $produitRepository
     * @param  mixed $request
     * @param  mixed $manager
     * @param  mixed $categoryRepository
     * @return void
     */
    public function edit(int $id, ProduitsRepository $produitRepository, Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository)
    {

        $body = json_decode($request->getContent(), true);

        $produit =  $produitRepository->find($id);
        $produit->setNom($body['name_produit']);
        $produit->setPrix($body['prix_produit']);
        $produit->setImage($body['image_produit']);
        $produit->setCreatedAt(new DateTime());

        $currentCategories = $produit->getCategories();

        foreach ($currentCategories as $category) {
            $produit->removeCategory($category);
        }

        foreach ($body['category_produit'] as $categoryLabel) {
            $persistedCategory = $categoryRepository->findOneBy(['label' => $categoryLabel]);
            $produit->addCategory($persistedCategory);
        }

        $manager->flush();

        return $this->json($produit);
    }
    /**
     * @Route("/api/admin/delete/{id}", name="api_admin_delete", methods={"GET"} )
     */
    /**
     * Delete the product
     *
     * @param  mixed $id
     * @param  mixed $produit
     * @param  mixed $manager
     * @return void
     */
    public function delete(int $id, ProduitsRepository $produit, EntityManagerInterface $manager)
    {
        $manager->remove($produit->find($id));
        $manager->flush();
        return new Response('Le produit a bien été supprimé');
    }
}
