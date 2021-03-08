<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\User;
use App\Repository\AvisRepository;
use App\Repository\FilmRepository;
use mysql_xdevapi\CrudOperationBindable;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AccueilController extends AbstractController
{
    /**
     * @Route("", name="accueil")
     * @param FilmRepository $filmRepository
     * @return Response
     */
    public function index(FilmRepository $filmRepository, AvisRepository $avisRepository, Security $security): Response
    {
        $tous = $filmRepository->findAll();
        $max = count($tous);
        foreach ($tous as $id)
        {
            $idFilm[] = $id->getId();
        }
        if($max>0)
        {
            for ($i=0;$i<$max;$i++) {
                $moyenne[$idFilm[$i]] = $avisRepository->moyenne($idFilm[$i]);
            }
        }
        else
        {
            $moyenne[]=null;
        }
        return $this->render('accueil/index.html.twig', [
            'films' => $filmRepository->findAll(),
            'avis' => $moyenne,
        ]);
    }
}
