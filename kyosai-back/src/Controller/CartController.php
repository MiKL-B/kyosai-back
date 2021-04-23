<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\CartRepository;
use App\Repository\UsersRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/panier", name="cart_index", methods={"GET"})
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
     * @Route("/panier/add/{id}", name="cart_add", methods={"PUT"})
     */
    public function add(Produits $produit, SessionInterface $session, CartRepository $cart, Request $request, UsersRepository $userRepository, EntityManagerInterface $manager)
    {

        //recuperation du panier
        $panier = $session->get('panier', []);

        //pour qu'on puisse pas ajouter un produit avec un id qui n'existe pas
        $id = $produit->getId();
        // if (!empty($panier[$id])) {
        //     $panier[$id]++;
        // } else {
        //     $panier[$id] = 1;
        // }
        // $session->set('panier', $panier);
        $tokenParts = explode(".", substr($request->headers->get('Authorization'), 7));
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
   
        $user = $userRepository->findOneBy(['email'=> $jwtPayload->username]);
      
        $user->addProduit($produit);
        $manager->persist($produit);
        $manager->flush();
        var_dump($id);
        // return $this->redirectToRoute('cart_index');
        return $this->json($id);
    }

    /**
     * Undocumented function
     * @Route("/test/panier", name="test", methods={"POST","GET"})
     */
    public function test(Request $request)
    {
        $authorizationHeader = $request->headers->get('Autorization');

        // return new Response(substr($request->headers->get('Authorization'), 7));
        $tokenParts = explode(".", substr($request->headers->get('Authorization'), 7));
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        return new  JsonResponse($jwtPayload);
    }
}
