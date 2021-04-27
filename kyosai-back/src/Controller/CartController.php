<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Users;
use App\Entity\Produits;
use App\Repository\CartRepository;
use App\Repository\UsersRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index", methods={"POST","GET"})
     */
    public function index(SessionInterface $session, ProduitsRepository $produitRepository, UsersRepository $userRepository, Request $request)
    {
        $tokenParts = explode(".", substr($request->headers->get('Authorization'), 7));
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        //ajouter produit au panier a l'utilisateur
        $user = $userRepository->findOneBy(['email' => $jwtPayload->username]);

        return $this->json($user->getCarts());
    }

    /**
     *@Route("/add/cart/{id}", name="add_cart", methods={"GET"})
     */
    public function add(Request $request, UsersRepository $userRepository, ProduitsRepository $produitsRepository, EntityManagerInterface $manager, Produits $produitEntity, CartRepository $cartRepository)
    {
        //decode token

        $tokenParts = explode(".", substr($request->headers->get('Authorization'), 7));
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        //ajouter produit au panier a l'utilisateur
        $user = $userRepository->findOneBy(['email' => $jwtPayload->username]);
        $newObj = new Cart();
        $newObj->setUser($user);
        //obtenir l'id du produits l'entité
        $id = $produitEntity->getId();
        //allez le chercher dans le dépot
        $produit = $produitsRepository->findOneBy(['id' => $id]);

        $result =  $cartRepository->count(['produit' => $produitEntity, 'user' => $user]);


        $currentCart = $cartRepository->findOneBy(['produit' => $produitEntity, 'user' => $user]);
        if ($result == 0) {
            $newObj->setQuantity(1);
            $newObj->setProduit($produit);
            $user->addCart($newObj);
            $manager->persist($user);
            $manager->persist($newObj);
            $manager->flush();
        } else {
            $currentCart->setQuantity($currentCart->getQuantity() + 1);
            $currentCart->setProduit($produit);
            $user->addCart($currentCart);
            $manager->persist($user);
            $manager->persist($currentCart);
            $manager->flush();
        }

        return $this->json($user->getCarts());
    }
    /**
     *@Route("/delete/cart/{id}", name="delete_cart", methods={"DELETE"})
     */
    public function delete(int $id, EntityManagerInterface $manager, CartRepository $cartRepository)
    {
        $cart = $cartRepository->findOneBy(['id' => $id]);
        $manager->remove($cart);
        $manager->flush();
        return new Response('Le produit a bien été supprimé');
    }
}
