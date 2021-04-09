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
    public function getEditContent(int $id, ProduitsRepository $produit)
    {
        return $this->json($produit->find($id));
    }
    /**
     * @Route("/api/admin/edit/{id}", name="api_admin_edit", methods={"POST","GET"})
     */
    public function edit(int $id, ProduitsRepository $produit, Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository)
    {

        $body = json_decode($request->getContent(), true);

        $produit->find($id)->setNom($body['name_produit']);
        $produit->find($id)->setPrix($body['prix_produit']);
        $produit->find($id)->setImage($body['image_produit']);
        $produit->find($id)->setCreatedAt(new DateTime());
        $persistedCategory = $categoryRepository->findOneBy(['label' => $body["category_produit"]]);
        $produit->find($id)->addCategory($persistedCategory);
        $manager->flush();

        return $this->json($produit->find($id));
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
