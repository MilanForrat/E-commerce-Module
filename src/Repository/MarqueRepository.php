<?php

namespace App\Repository;

use App\Entity\Marque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Marque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marque[]    findAll()
 * @method Marque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marque::class);
    }

    /**
     * Returns marque by id
     * @return void
     */
    public function viewById($id){
        $queryBuilder = $this->createQueryBuilder('marque');
        $queryBuilder->where($queryBuilder->expr()->eq('marque.id', $id));
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}

