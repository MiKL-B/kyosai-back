<?php

namespace App\Controller;

use DateTime;
use App\Repository\CategoryRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEditPostController extends AbstractController
{

    /**
     * @Route("/api/admin/edit/view/{id}", name="api_admin_edit_view", methods={"POST","GET"})
     */
    public function getEditContent(int $id,  ProduitsRepository $produit)
    {

        return $this->json($produit->find($id));
    }
    /**
     * @Route("/api/admin/edit/{id}", name="api_admin_edit", methods={"POST","GET"})
     */
    public function edit(int $id,  ProduitsRepository $produitRepository, Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository)
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
     * @Route("/api/admin/delete/{id}", name="api_admin_delete", methods={"POST","GET"} )
     */
    public function delete(int $id, ProduitsRepository $produit, EntityManagerInterface $manager)
    {
        $manager->remove($produit->find($id));
        $manager->flush();
        return new Response('Le produit a bien été supprimé');
    }
}
