<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index", methods={"POST","GET"})
     */
    public function index(SessionInterface $session, ProduitsRepository $produitRepository)
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $produitRepository->find($id),
                'quantity' => $quantity,
            ];
        };
        $total = 0;
        //foreach ($panierWithData as $item) {
        //    $totalItem = $item['product']->getPrix() * $item['quantity'];
         //   $total += $totalItem;
       // }

        return $this->json([
            'session' => $session,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }
    /**
     * @Route("/panier/add/{id}", name="cart_add", methods={"POST","GET"})
     */
    public function add($id, SessionInterface $session)
    {

        //recuperation du panier
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);
        return $this->json(['panier' => $panier]);
    }
}
