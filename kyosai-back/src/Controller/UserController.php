<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
     * @Route("/api/login_check", name="api_login_check", methods={"POST","GET"})
     */
    public function loginCheck(): JsonResponse
    {

        $user = $this->getUser();
        return new JsonResponse($user);
    }
}
