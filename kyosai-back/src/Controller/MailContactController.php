<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailContactController extends AbstractController
{
    /**
     * @Route("/mail/contact", name="mail_contact")
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        $body = json_decode($request->getContent(), true);
        $email = (new Email())
            // from = user
            ->from($body['email'])
            // to = asso
            ->to('michaelbecquer7@gmail.com')
            //     //->cc('cc@example.com')
            //     //->bcc('bcc@example.com')
            //     //->replyTo('fabien@example.com')
            //     //->priority(Email::PRIORITY_HIGH)
            ->subject($body['name'])

            ->html($body['message']);

        $mailer->send($email);
        dd($email);
        return $this->json($mailer);
    }
}
