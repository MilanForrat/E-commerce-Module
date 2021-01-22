<?php

namespace App\Repository;

use App\Data\SearchData;
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
   public function findSearch(SearchData $search): array{    // SearchData est le type de paramètre et que l'on nommera search
    $query = $this
        ->createQueryBuilder('p')
        ->select('c', 'p')              // on met cette requête afin de diminuer le nombre de requêtes individuelles, 
        ->join('p.categories', 'c');  //et les grouper

        if(!empty($search->q)){    // si ma recherche n'est pas vide, je recupère ma propriété q
            $query = $query
            ->andWhere('p.name LIKE :q')    // on veut que le nom du produit ressemble à la propriété de recherche q
            ->setParameter('q', "%{$search->q}%");   // les % permettent les recherches partielles en correspondance avec le mot clé de ma recherche
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


       return $query->getQuery()->getResult();
   }
}
