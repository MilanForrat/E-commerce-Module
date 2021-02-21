<?php

namespace App\Controller;

use App\Form\SearchForm;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;

class NavBar extends AbstractController
{
    protected $requestStack;

    /**
     * Construct that should deal with the subRequest
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/navbar", name="navbar")
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, RequestStack $requestStack)
    {
        $products = $productRepository->findBy(['status' => 1]);
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        $formSearch = $this->createForm(SearchForm::class);

        $searchRequest = $this->requestStack->getCurrentRequest();  // je demande au formulaire de traiter la requête

        $searchData = $formSearch->handleRequest($searchRequest);

        dump($searchData->get('search')->getData());  //je test ma requête et vérifie que je récupère bien mes éléments recherchés
        
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

        $productsMain = $productRepository->findTopEight();

        return $this->render('navbar/_navbar.html.twig', [
            'formSearch' => $formSearch->createView(),
            'productsMain' => $productsMain,
            'products' => $products,
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }
}
