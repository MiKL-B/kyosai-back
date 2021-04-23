<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\CartRepository;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    public function test(Request $request)
    {


        $autorizationHeader = $request->headers->get('Autorization');


        return substr($autorizationHeader, 7);
    }
}
