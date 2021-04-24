<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Produits;
use App\Repository\UsersRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

        // $panier = $session->get('panier', []);

        // $panierWithData = [];
        // $total = 0;

        // foreach ($panier as $id => $quantity) {

        //     $produit = $produitRepository->find($id);
        //     $panierWithData[] = [
        //         "produit" => $produit,
        //         'quantity' => $quantity
        //     ];
        //     $total += $produit->getPrix() * $quantity;
        // };

        // return $this->json([
        //     'panierWithData' => $panierWithData,
        //     'total' => $total,

        // ]);

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
     * Undocumented function
     *@Route("/test/user/{id}", name="test_user", methods={"GET"})
     */
    public function test(Request $request, UsersRepository $userRepository, ProduitsRepository $produitsRepository, EntityManagerInterface $manager, Produits $produitEntity)
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
        // différencier produits qui vient de entity  produit(s)
        // et produit qui vient de repository         produit
        //obtenir l'id du produits l'entité
        $id = $produitEntity->getId();
        //allez le chercher dans le dépot
        $produit = $produitsRepository->findOneBy(['id' => $id]);
        $newObj->setProduit($produit);
        $newQuantity = 0;
        if ($newObj->getQuantity() == 1) {
            $newObj->setQuantity($newQuantity++);
        } else {
            $newObj->setQuantity(1);
        }

        $user->addCart($newObj);
        $manager->persist($user);
        $manager->persist($newObj);
        $manager->flush();

        return $this->json($user->getCarts());
    }
}
