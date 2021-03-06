<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UserChecker implements UserCheckerInterface{
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof User){
            return;
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if(!$user instanceof User){
            return;
        }
        if(!$user->getActivation()){
            throw new CustomUserMessageAccountStatusException("Votre compte n'est pas activer, veuillez consulter vos e-mail pour l'activer avant le {$user->getDateExpiration()->format('d/m/Y à H\hi')}");

        }
    }
}