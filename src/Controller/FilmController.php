<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Images;
use App\Entity\Reservation;
use App\Entity\Seance;
use App\Entity\User;
use App\Entity\Videos;
use App\Form\FilmType;
use App\Form\ReservationType;
use App\Repository\AvisRepository;
use App\Repository\FilmRepository;
use App\Repository\SeanceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class FilmController extends AbstractController
{
    /**
     * @Route("/moderateur/film", name="film_index", methods={"GET"})
     */
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    /**
     * @Route("/film/type/{tags}", name="film_type")
     */
    public function type(FilmRepository $filmRepository, $tags){
        $films = $filmRepository->type($tags);
        return $this->render('film/type.html.twig', [
            'films' => $films
        ]);
    }

    /**
     * @Route("/moderateur/film/new", name="film_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('Image')->getData();
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
            $image->move(
              $this->getParameter('images_directory'),
              $fichier
            );
            $img = new Images();
            $img->setNom($fichier);
            $film->setImages($img);

            $video = $form->get('Video')->getData();
            $fichvideo = md5(uniqid()).'.'.$video->guessExtension();
            $video->move(
                $this->getParameter('video_directory'),
                $fichvideo
            );
            $vid = new Videos();
            $vid->setNom($fichvideo);
            $film->setVideos($vid);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('film_index');
        }

        return $this->render('film/new.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/moderateur/film/{id}", name="film_show", methods={"GET"})
     */
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    /**
     * @Route("/moderateur/film/{id}/edit", name="film_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Film $film): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('Image')->getData();
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $img = new Images();
            $img->setNom($fichier);
            $film->setImages($img);

            $video = $form->get('Video')->getData();
            $fichvideo = md5(uniqid()).'.'.$video->guessExtension();
            $video->move(
                $this->getParameter('video_directory'),
                $fichvideo
            );
            $vid = new Videos();
            $vid->setNom($fichvideo);
            $film->setVideos($vid);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('film_index');
        }

        return $this->render('film/edit.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/moderateur/film/{id}", name="film_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Film $film): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('film_index');
    }


    /**
     * @param FilmRepository $filmRepository
     * @param $nom
     * @return Response
     * @Route("/{id}/{nom}/description", name="film_description")
     */
    public function description(PaginatorInterface $paginator,FilmRepository $filmRepository,AvisRepository $avisRepository, $nom, $id, Request $request): Response
    {
        $allavis = $avisRepository->findBy(
            ['idFilm' => $id]
        );
        $avis = $paginator->paginate(
            $allavis,
            $request->query->getInt('page', 1), 6
        );

        $note =$avisRepository->moyenne($id);
        return $this->render('film/description.html.twig',[
            'descriptions' => $filmRepository->findBy(
                ['Nom' => $nom]
            ),
            'avis' => $avis,
            'note' => $note
        ]);
    }
}
