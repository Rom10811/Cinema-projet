<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Film;
use App\Entity\User;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/avis")
 */
class AvisController extends AbstractController
{
    /**
     * @Route("/{id}/cancel", name="avis_remove")
     * @param $id
     * @param AvisRepository $avisRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function supprimer($id, AvisRepository $avisRepository)
    {
        $avis = $avisRepository->findOneBy(
            [
                'id' => $id
            ]
        );
        $em = $this->getDoctrine()->getManager();
        $em->remove($avis);
        $em->flush();
        return $this->redirectToRoute('accueil');
    }
    /**
     * @Route("/{id}/confirm", name="avis_confirm")
     * @param $idavis
     * @param AvisRepository $avisRepository
     */
    public function confirmer($id, AvisRepository $avisRepository)
    {
        $avis = $avisRepository->findOneBy([
            'id' => $id
            ]
        );

        $avis->setVisible(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($avis);
        $em->flush();
        return $this->redirectToRoute('accueil');
    }
    /**
     * @Route("/", name="avis_index", methods={"GET"})
     */
    public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{idFilm}/new", name="avis_new", methods={"GET","POST"})
     */
    public function new(FilmRepository  $filmRepository, Request $request, Security $security, $idFilm): Response
    {
        $avi = new Avis();
        $user = new User();
        $film = new Film();
        $user = $security->getUser();
        $film = $filmRepository->findOneBy(
            ['id' => $idFilm]
        );
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avi = $avi->setIdUser($user);
            $avi = $avi->setDateCreation(new \DateTime());
            $avi = $avi->setIdFilm($film);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($avi);
            $entityManager->flush();
            return $this->redirectToRoute('accueil');
        }

        return $this->render('avis/new.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avis_show", methods={"GET"})
     */
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avis_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Avis $avi): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avis_index');
        }

        return $this->render('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avis_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Avis $avi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avis_index');
    }
}
