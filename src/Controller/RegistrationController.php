<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer, UserRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setToken($this->generateToken());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->mailer->sendEmail($user->getEmail(), $user->getToken());
            $this->addFlash('message', 'Un mail d\'activation vous sera envoyé et vous disposez d\'une période de 24 heures pour valider votre compte');
            return $this->redirectToRoute('accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation/{token}", name="confirm_inscription")
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmation(string $token)
    {
        $date = new \DateTime();
        $date->getTimestamp();
        $user = $this->userRepository->findOneBy(["token" => $token]);
        if($date > $user->getDateExpiration())
        {
            $this->addFlash('danger', 'La periode de validité du mail est dépassée !');
            return $this->redirectToRoute('accueil');
        }
        else
        {
            if($user){
                $user->setToken(null);
                $user->setActivation(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('message', 'Votre compte a bien été validé !');
                return $this->redirectToRoute('accueil');
            }
            else{
                $this->redirectToRoute('accueil');
            }
        }
    }

    public function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
