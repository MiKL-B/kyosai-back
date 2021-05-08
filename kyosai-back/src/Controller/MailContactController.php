<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Manages the sending of mail
 * @author Michael BECQUER
 */
class MailContactController extends AbstractController
{
    /**
     * @Route("/mail/contact", name="mail_contact")
     */
    /**
     * Sends a mail to the association
     *
     * @param  mixed $request
     * @param  mixed $mailer
     * @return void
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        $body = json_decode($request->getContent(), true);
        $email = (new Email())
            // from = user
            ->from($body['email'])
            // to = asso

            ->replyTo($body['email'])
            ->to('YourAdress@mail.fr')
            ->subject($body['name'])

            ->html($body['message']);

        $mailer->send($email);

        return $this->json($mailer);
    }
}
