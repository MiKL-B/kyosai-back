<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDeletePostController extends AbstractController
{
    /**
     * @Route("/api/admin/delete/{id}", name="api_admin_delete", methods={"POST","GET"} )
     */
    public function delete(ProduitsRepository $produit, EntityManagerInterface $manager)
    {
        $manager->remove($produit);
        $manager->flush();

        return new Response("supression");
    }
}
