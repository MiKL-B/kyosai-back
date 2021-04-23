<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Role;
use App\Entity\Users;
use App\Form\RegistrationType;
use App\Repository\ProduitsRepository;
use App\Repository\RoleRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST","GET"})
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator, RoleRepository $roleRepository): response
    {


        $user = new Users();
        $body = json_decode($request->getContent(), true);
        $user->setNom($body["firstname"]);
        $user->setEmail($body["email"]);
        $user->setPassword($body["mdp"]);
        $monRole = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
        $user->addRole($monRole);
        $hash = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            /*
         * Uses a __toString method on the $errors variable which is a
         * ConstraintViolationList object. This gives us a nice string
         * for debugging.
         */
            $errorsString = (string) $errors;

            return $this->json(['errors' => $errors], 409);
        }
        $manager->persist($user);
        $manager->flush();
        return $this->json($body['firstname']);
    }

    /**
     * Undocumented function
     *@Route("/test/user", name="test_user", methods={"GET"})
     */
    public function test(Request $request, UsersRepository $userRepository, ProduitsRepository $produitsRepository, EntityManagerInterface $manager)
    {
        //decode token
        $tokenParts = explode(".", substr($request->headers->get('Authorization'), 7));
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        $user = $userRepository->findOneBy(['email' => $jwtPayload->username]);
        $newObj = new Cart();
        $newObj->setUser($user);
        $produit = $produitsRepository->findOneBy(['nom' => 'shenron']);
        $newObj->setProduit($produit);
        $newObj->setQuantity(1);
        $user->addCart($newObj);
        $manager->persist($user);
        $manager->persist($newObj);
        $manager->flush();

        return $this->json($user->getCarts());
    }
}
