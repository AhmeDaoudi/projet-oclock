<?php

namespace App\Repository;

use App\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Score::class);
    }


    public function findGameInfo($user, $game){

        $qb = $this->createQueryBuilder('s')
            ->join('s.user', 'u')
            ->join('s.game', 'g')
            ->where('u.id = :user')
            ->andWhere('g.id = :game')
            ->setParameter('user', $user)
            ->setParameter('game', $game)
            ->setMaxResults(18)
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function averageScore($game){

        $qb = $this->createQueryBuilder('s')
        ->join('s.game', 'g')
        ->select('avg(s.value), count(s.value)')
        ->where('g.id = :game')
        ->setParameter('game', $game)
        ->getQuery()
        ->getResult();

        return $qb;
    }


}
