<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Entity\User;
use App\Form\ReservationType;
use App\Form\SeanceType;
use App\Repository\SalleRepository;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class SeanceController extends AbstractController
{
    /**
     * @Route("/moderateur/seance/", name="seance_index", methods={"GET"})
     */
    public function index(SeanceRepository $seanceRepository): Response
    {
        return $this->render('seance/index.html.twig', [
            'seances' => $seanceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/moderateur/seance/new", name="seance_new", methods={"GET","POST"})
     */
    public function new(Request $request, SalleRepository $salleRepository): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);
        $salle = $seance->getIdSalle();
        if ($form->isSubmitted() && $form->isValid()) {
            $seance->setPlacesRestantes($salle->getTotalPlaces());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($seance);
            $entityManager->flush();

            return $this->redirectToRoute('seance_index');
        }

        return $this->render('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/moderateur/seance/{id}", name="seance_show", methods={"GET"})
     */
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/moderateur/seance/{id}/edit", name="seance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seance $seance): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('seance_index');
        }

        return $this->render('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/moderateur/seance/{id}", name="seance_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Seance $seance): Response
    {
        if ($this->isCsrfTokenValid('delete' . $seance->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('seance_index');
    }


    /**
     * @param Request $request
     * @param Film $film
     * @return Response
     * @Route("/{id}/seances", name="film_seance")
     */
    public function reserv(SeanceRepository $seanceRepository, $id,Security $security, Request $request): Response
    {
        $user = new User();
        $user= $security->getUser();
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user == null)
            {
                return $this->redirectToRoute('app_login');
            }
            $reservation->setIdUser($user);
            $nbr = $reservation->getNbrPlaces();
            $idseance = $reservation->getIdFilm()->getId();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
            $seanceRepository->reservation($idseance, $nbr);
            return $this->redirectToRoute('reservation_consult');
        }

        return $this->render('seance/seances.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
            'films' => $seanceRepository->findBy(
                ['idFilm' => $id],
                ['heure' => 'ASC']
            ),
        ]);
    }
}
