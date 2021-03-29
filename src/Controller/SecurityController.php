<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/reset-password", name="app_password")
     */
    public function password(Request $request, UserRepository $userRepository, Mailer $mailer, RegistrationController $registrationController){
        $form = $this->createForm(ResetPassType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $donnees = $form->getData();
            $user = $userRepository->findOneBy(["email" => $donnees['email']]);

            if(!$user)
            {
                $this->addFlash('danger', "Cette adresse mail n'existe pas");
                return $this->redirectToRoute('app_login');
            }
            $token = $registrationController->generateToken();

            try {
                $user->setResetToken($token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            catch(\Exception $e)
            {
                $this->addFlash('warning', 'Une erreur est survenue:' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }
            $this->mailer->resetPassword($user->getEmail(),$user->getResetToken());
            $this->addFlash('message', 'Un email de reinitialisation de mot de passe a été envoyé');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/resetpass.html.twig',['emailForm'=>$form->createView()]);
    }


    /**
     * @Route("/reset-pass/{token}", name="app_reset_password")
     */
    public function resetPassword(string $token, Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if (!$user)
        {
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('app_login');
        }
        if($request->isMethod('POST'))
        {
            $user->setResetToken(null);
            $user->setPassword($userPasswordEncoder->encodePassword($user, $request->request->get('password')));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Mot de passe modifié avec succès');

            return $this->redirectToRoute('app_login');
        }
        else{
            return $this->render('security/changepassword.html.twig', [
                'token' => $token
            ]);
        }
    }
}
