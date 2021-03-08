<?php

namespace App\Repository;

use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Seance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seance[]    findAll()
 * @method Seance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    public function reservation($idseance, $nbr)
    {
        $query = $this->getEntityManager()->createQueryBuilder('s');
        $query
            ->update()
            ->from('\App\Entity\Seance', 's')
            ->set('s.PlacesReserves', 's.PlacesReserves + :nbr')
            ->where('s.id=:idseance')
            ->setParameters(new ArrayCollection(array(
                new Parameter('idseance', $idseance),
                new Parameter('nbr', $nbr)
            )))
            ->getQuery()
            ->execute();
        $query2 = $this->getEntityManager()->createQueryBuilder('s2');
        $query2->update()
            ->from('\App\Entity\Seance', 's')
            ->set('s.PlacesRestantes', 's.PlacesRestantes - :nbr')
            ->where('s.id=:idseance')
            ->setParameters(new ArrayCollection(array(
                new Parameter('idseance', $idseance),
                new Parameter('nbr', $nbr)
            )))
            ->getQuery()
            ->execute();
    }
}
