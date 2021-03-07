<?php

namespace App\Repository;

use App\Data\CategoryData;
use App\Data\FilterData;
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
     * @return void 
     */
     public function findSearch($search = null){   
        $query = $this->createQueryBuilder('product');
        $query->where('product.status = 1');
        //dump($search);
        if($search != null){    // si ma recherche n'est pas vide
            $query
            ->andWhere('MATCH_AGAINST (product.name, product.content, product.description) AGAINST (:search boolean)>0')    
            ->setParameter('search', $search);  
        }

        return $query->getQuery()->getResult();
   }

   /**
    * Returns filter results
    * @return  Product[]
    */
    public function findWithFilters(FilterData $data):array{

        $query = $this
        ->createQueryBuilder('p');
         // on met cette requête afin de diminuer le nombre de requêtes individuelles, 


        if(!empty($data->min)){
            $query = $query
            ->andWhere('p.price >= :min')    // on veut que le prix du produit soit supérieur ou égal à la valeur minimale passée en requête
            ->setParameter('min', $data->min);
        }

        if(!empty($data->max)){
            $query = $query
            ->andWhere('p.price <= :max')    // on veut que le prix du produit soit inférieur ou égal à la valeur minimale passée en requête
            ->setParameter('max', $data->max); 
        }

        return $query = $query->getQuery()->getResult();
    }

     /**
    * Returns category filter results
    * @return  Product[]
    */
    public function findWithCategories(CategoryData $data):array{

        $query = $this
        ->createQueryBuilder('p');
        dump($data);
        if (!empty($data->categories)) {
            $query=$query
            ->Where('p.category = (:categories)')
            ->setParameter('categories', $data->categories);  // j'indique que mon paramètre categories correspondant à la liste de recherche categories
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
        //$queryBuilder->orderBy('product.rating');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    /**
     * Gets the product by its id
     * @return void
     */
    public function viewById($id){

        $queryBuilder = $this->createQueryBuilder('product');
        $queryBuilder->where($queryBuilder->expr()->eq('product.id', $id));
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    /**
     * Returns $number associated products with categories
     * @return void
     */
    public function getAssociatedProducts($number, $id){
        $queryBuilder = $this->createQueryBuilder('product');
        $queryBuilder->innerJoin('product.category', 'product_category');

        $queryBuilder->where(
            $queryBuilder->expr()->eq('product_category.id', $id)
        );
        //dump($number);
        $queryBuilder->setMaxResults($number);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
