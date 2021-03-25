<?php

namespace App\Controller;


use App\Entity\Produits;
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
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $produit = new Produits();
        $body = json_decode($request->getContent(), true);
        $produit->setNom($body["name_produit"]);
        $produit->setPrix($body["prix_produit"]);
        $produit->setImage($body["image_produit"]);
        //$produit->setCreatedAt($body["date_produit"]);
        $manager->persist($produit);
        $manager->flush();
        return $this->json($body["name_produit"]);
    }
}
