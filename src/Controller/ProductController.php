<?php

namespace App\Controller;

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
    public function index(ProductRepository $repository, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request){

        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        $products = $repository->findBy(['status' => 1]);

        $formFilter = $this->createForm(ProductFiltersType::class);
        $filterForm = $formFilter->handleRequest($request);  // je demande au formulaire de traiter la requête
        
        if($formFilter->isSubmitted() && $formFilter->isValid()){
            $filteredProducts = $repository->findWithFilters($filterForm->get('categories')->getData()
        );
        }

        $formSearch = $this->createForm(SearchForm::class);
        $searchform = $formSearch->handleRequest($request);  // je demande au formulaire de traiter la requête

        // dd($data);  je test ma requête et vérifie que je récupère bien mes éléments recherchés
        
        if($formSearch->isSubmitted() && $formSearch->isValid()){
            $searchedProducts = $repository->findSearch($searchform->get('search')->getData()
        );
        }

        return $this->render('product/index.html.twig', [
            'formFilter' => $formFilter->createView(),
            'formSearch' => $formSearch->createView(),
            'products' => $products, 
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

}