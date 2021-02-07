<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Gets all the products linked to a search
     *
     * @return Product[]
     */
     public function findSearch($search): array{   
        $query = $this
        ->createQueryBuilder('p')
        ->select('c', 'p')              // on met cette requête afin de diminuer le nombre de requêtes individuelles, 
        ->join('p.categories', 'c');  //et les grouper

        if(!empty($search->q)){    // si ma recherche n'est pas vide, je recupère ma propriété q
            $query = $query
            ->Where('p.status = 1')
            ->andWhere('MATCH_AGAINST(p.name, p.content. p.description) AGAINST(:search boolean)>0')    
            ->setParameter('search', $search);   // les % permettent les recherches partielles en correspondance avec le mot clé de ma recherche
        }

        if(!empty($search->min)){
            $query = $query
            ->andWhere('p.price >= :min')    // on veut que le prix du produit soit supérieur ou égal à la valeur minimale passée en requête
            ->setParameter('min', $search->min);   // ici pas de % car c'est une référence précise que l'on demande
        }

        if(!empty($search->max)){
            $query = $query
            ->andWhere('p.price <= :max')    // on veut que le prix du produit soit inférieur ou égal à la valeur minimale passée en requête
            ->setParameter('max', $search->max); 
        }

        if(!empty($search->promo)){
            $query = $query
            ->andWhere('p.promo = 1');    // on veut afficher les produits en promo (1 car true)
        }

        if (!empty($search->categories)) {
            $query=$query
            ->andWhere('c.id IN (:categories)')  // on veut afficher les catégories 'c' envoyées par la liste :categories
            ->setParameter('categories', $search->categories);  // j'indique que mon paramètre categories correspondant à la liste de recherche categories
        }

        return $query = $query->getQuery()->getResult();
   }

    /**
     * Returns the number of produtcs
     */
    public function getTotalProducts(){
        $query= $this->createQueryBuilder('p')
        ->select('COUNT(p)')     // afin de compter le nombre de produits
        ->where('p.status = 1')
        ;

        return $query->getQuery()->getSingleScalarResult();    // permet d'avoir un résultat qui n'est ni un tableau ni un objet (donc soit : int / string ...)
    }

    public function findTopEight(){

        $queryBuilder = $this->createQueryBuilder('product');
        $queryBuilder->where('product.status = 1');
        $queryBuilder->setMaxResults(5);
        $queryBuilder->orderBy('product.rating');
        $query = $queryBuilder->getQuery();

        return $query->getResult();


    }
}
