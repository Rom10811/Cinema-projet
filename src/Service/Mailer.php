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
        $mail = (new TemplatedEmail())
            ->from('contact@romain-duciel.ovh')
            ->to(new Address($email))
            ->subject('Merci pour votre inscription')
            ->htmlTemplate('email/mail.html.twig')
            ->context([
                'token' => $token,
            ])
        ;

        $this->mailer->send($mail);
    }

    public function resetPassword($email, $token)
    {
        $mail1 = (new TemplatedEmail())
            ->from('contact@romain-duciel.ovh')
            ->to(new Address($email))
            ->subject('Reinitialisation de votre mot de passe')
            ->htmlTemplate('email/password.html.twig')
            ->context([
                'token' => $token,
            ]);
        $this->mailer->send($mail1);
    }
}