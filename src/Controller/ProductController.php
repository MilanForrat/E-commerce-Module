<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\FilterFormType;
use App\Form\ProductFiltersType;
use App\Form\SearchForm;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use App\Services\NavbarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $data = new SearchData();

        $formFilter = $this->createForm(ProductFiltersType::class, $data);
        $formSearch = $this->createForm(SearchForm::class);

        if ($request->isMethod('GET')) {
            $formFilter->handleRequest($request);
            $searchRequest = $formSearch->handleRequest($request);

            if ($formFilter->isSubmitted() && $formFilter->isValid()) {
                $products = $productRepository->findWithFilters($data);
                dump($data);
            }

            if ($formSearch->isSubmitted() && $formSearch->isValid()) {
                $products = $productRepository->findSearch(
                    $searchRequest->get('search')->getData()
                );
                return $this->render('main/search-results.html.twig', [
                    'formSearch' => $formSearch->createView(),
                    'products' => $products,
                    'categories' => $categories,
                    'marques' => $marques,
            ]);
            }
        }
        
        return $this->render('product/index.html.twig', [
            'formFilter' => $formFilter->createView(),
            'formSearch' => $formSearch->createView(),
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

        $formSearch = $this->createForm(SearchForm::class);
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