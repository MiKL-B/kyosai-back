<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user", name="api_user_index", methods={"POST","GET"})
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): response
    {
        $user = new Utilisateurs();
        $body = json_decode($request->getContent(), true);
        $user->setNom($body["firstname"]);
        $user->setEmail($body["email"]);
        $user->setMotDePasse($body["mdp"]);
        $hash = $encoder->encodePassword($user, $user->getMotdePasse());
        $user->setMotDePasse($hash);
        $manager->persist($user);
        $manager->flush();


        return $this->json($body['firstname']);
    }
}
