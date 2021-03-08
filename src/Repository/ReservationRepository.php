<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use http\QueryString;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findAll(){
        return $this->findBy(array(), array('id'=>'ASC'));
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function cancel($idseance, $nbr, $idreservation)
    {
        $query = $this->getEntityManager()->createQueryBuilder('r');
        $query->update()
        ->from('\App\Entity\Seance', 's')
        ->set('s.PlacesReserves', 's.PlacesReserves - :nbr')
        ->where('s.id=:idseance')
        ->setParameters(new ArrayCollection(array(
            new Parameter('idseance', $idseance),
            new Parameter('nbr', $nbr)
        )))
        ->getQuery()
        ->execute();
        $query2 = $this->getEntityManager()->createQueryBuilder('r2');
        $query2->update()
        ->from('\App\Entity\Seance', 's')
        ->set('s.PlacesRestantes', 's.PlacesRestantes + :nbr')
        ->where('s.id=:idseance')
        ->setParameters(new ArrayCollection(array(
            new Parameter('idseance', $idseance),
            new Parameter('nbr', $nbr)
        )))
        ->getQuery()
        ->execute();
        $query3 = $this->getEntityManager()->createQueryBuilder('r3');
        $query3->update()
            ->from('\App\Entity\Reservation', 'r')
            ->set('r.Etat', 2)
            ->where('r.id=:idreservation')
            ->setParameter('idreservation', $idreservation)
            ->getQuery()
            ->execute();
    }

    public function valider($idreservation)
    {
        $query = $this->getEntityManager()->createQueryBuilder('r3');
        $query->update()
            ->from('\App\Entity\Reservation', 'r')
            ->set('r.Etat', 0)
            ->where('r.id=:idreservation')
            ->setParameter('idreservation', $idreservation)
            ->getQuery()
            ->execute();
    }
}
