<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Returns category by id
     * @return void
     */
    public function viewById($id){
        $queryBuilder = $this->createQueryBuilder('category');
        $queryBuilder->where($queryBuilder->expr()->eq('category.id', $id));
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

     /**
     * Returns the number of categories
     */
    public function getTotalCategories(){
        $query= $this->createQueryBuilder('c')
        ->select('COUNT(c)')     // afin de compter le nombre de categories
        ;

        return $query->getQuery()->getSingleScalarResult();    // permet d'avoir un résultat qui n'est ni un tableau ni un objet (donc soit : int / string ...)
    }
}
