<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\CartRepository;
use App\Repository\ProduitsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index", methods={"POST","GET"})
     */
    public function index(SessionInterface $session, ProduitsRepository $produitRepository)
    {

        $panier = $session->get('panier', []);

        $panierWithData = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {

            $produit = $produitRepository->find($id);
            $panierWithData[] = [
                "produit" => $produit,
                'quantity' => $quantity
            ];
            $total += $produit->getPrix() * $quantity;
        };

        return $this->json([
            'panierWithData' => $panierWithData,
            'total' => $total,

        ]);
    }
    /**
     * @Route("/panier/add/{id}", name="cart_add", methods={"POST","GET"})
     */
    public function add(Produits $produit, SessionInterface $session, CartRepository $cart)
    {

        //recuperation du panier
        $panier = $session->get('panier', []);

        //pour qu'on puisse pas ajouter un produit avec un id qui n'existe pas
        $id = $produit->getId();
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);


        return $this->redirectToRoute('cart_index');
    }

    /**
     * Undocumented function
     * @Route("/test/panier", name="test", methods={"POST","GET"})
     */
    public function test(AuthenticationUtils $authenticationUtils, Security $security)
    {


        $user =  $this->get('security.token_storage')->getToken()->getUser();


        return new JsonResponse($user);
    }
}
