<?php

namespace App\Controller;

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
    public function getEditContent(int $id, ProduitsRepository $produit, Request $request, EntityManagerInterface $manager)
    {
        return $this->json($produit->find($id));
    }
    /**
     * @Route("/api/admin/edit/{id}", name="api_admin_edit", methods={"POST","GET"})
     */
    public function edit(int $id, ProduitsRepository $produit, Request $request, EntityManagerInterface $manager)
    {

        $body = json_decode($request->getContent(), true);


        $produit->find($id)->setNom($body['name_produit']);
        $manager->flush();
        return $this->json($produit->find($id));
        // $produit->find($id)->setPrix($body['prix_produit']);
        // $produit->find($id)->setImage($body['image_produit']);
        // $produit->find($id)->setCreatedAt(new DateTime());

    }
}
