<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request)
    {
        $products = $productRepository->findBy(['status' => 1]);
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

        $productsMain = $productRepository->findTopEight();

        return $this->render('main/index.html.twig', [
            'formSearch' => $formSearch->createView(),
            'productsMain' => $productsMain,
            'products' => $products,
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }
}
