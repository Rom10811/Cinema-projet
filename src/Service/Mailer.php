<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $token)
    {
        $email = (new TemplatedEmail())
            ->from('contact@romain-duciel.ovh')
            ->to(new Address($email))
            ->subject('Merci pour votre inscription')
            ->htmlTemplate('email/mail.html.twig')
            ->context([
                'token' => $token,
            ])
        ;

        $this->mailer->send($email);
    }
}