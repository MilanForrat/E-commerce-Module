<?php

namespace App\Controller;

use App\Data\CategoryData;
use App\Data\FilterData;
use App\Data\SearchData;
use App\Form\CategoryFilterType;
use App\Form\ProductFiltersType;
use App\Form\SearchFormType;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products", name="products_")
 * @package App\Controller
 */
class ProductController extends AbstractController
{

    /**
     * @Route("/", name="liste")
     * @return void
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request){

        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findBy(['status' => 1]);

        $filterData = new FilterData();
        $searchData = new SearchData();
        $categoryData = new CategoryData();

        $formFilter = $this->createForm(ProductFiltersType::class, $filterData);
        $formSearch = $this->createForm(SearchFormType::class, $searchData);
        $formCategoryFilter = $this->createForm(CategoryFilterType::class, $categoryData);

        $formFilter->handleRequest($request);
        $formSearch->handleRequest($request);
        $formCategoryFilter->handleRequest($request);

        dump($request);

        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            $productsFiltered = $productRepository->findWithFilters($formFilter->getData());

            dump($productsFiltered);
            return $this->render('main/filter-results.html.twig', [
                'formSearch' => $formSearch->createView(),
                'formFilter' => $formFilter->createView(),
                'productsFiltered' => $productsFiltered,
                'products' => $products,
                'categories' => $categories,
                'marques' => $marques,
            ]);
        }

        if ($formCategoryFilter->isSubmitted() && $formCategoryFilter->isValid()) {
            $categoriesFiltered = $productRepository->findWithCategories($formCategoryFilter->getData());

            
            dump($request);
            dump($categoriesFiltered);

            return $this->render('main/category-results.html.twig', [
                'formSearch' => $formSearch->createView(),
                'formFilter' => $formFilter->createView(),
                'formCategoryFilter' => $formCategoryFilter->createView(),
                'categoriesFiltered' => $categoriesFiltered,
                'products' => $products,
                'categories' => $categories,
                'marques' => $marques,
            ]);
        }

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $products = $productRepository->findSearch(
                $formSearch->get('search')->getData()
            );
            return $this->render('main/search-results.html.twig', [
                'formSearch' => $formSearch->createView(),
                'formFilter' => $formFilter->createView(),
                'products' => $products,
                'categories' => $categories,
                'marques' => $marques,
            ]);
        }
    
        return $this->render('product/index.html.twig', [
            'formFilter' => $formFilter->createView(),
            'formSearch' => $formSearch->createView(),
            'formCategoryFilter' => $formCategoryFilter->createView(),
            'products' => $products, 
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    /**
     * @Route("/details/{id}", name="detail", methods={"GET"}, requirements={"id"="\d+"}))
     * @return void
     */
    public function productDetails($id, ProductRepository $productRepository, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request){

        $productById = $productRepository->viewById($id);
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        $formSearch = $this->createForm(SearchFormType::class);
        $searchRequest = $formSearch->handleRequest($request);  // je demande au formulaire de traiter la requête

        //dump($searchRequest->get('search')->getData());  //je test ma requête et vérifie que je récupère bien mes éléments recherchés
        
        if($formSearch->isSubmitted() && $formSearch->isValid()){
            $products = $productRepository->findSearch($searchRequest->get('search')->getData()     
        );
            return $this->render('main/search-results.html.twig', [
                'formSearch' => $formSearch->createView(),
                
                'products' => $products,
                'categories' => $categories,
                'marques' => $marques,
        ]);
        }
        //dump($productById);

        return $this->render('product/details.html.twig', [
            'productById' => $productById,
            'formSearch' => $formSearch->createView(),
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }
}