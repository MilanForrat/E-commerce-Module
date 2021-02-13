<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension{
    public function navBar(ProductRepository $productRepository, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request){

        $products = $productRepository->findBy(['status' => 1]);
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        $formSearch = $this->createForm(SearchForm::class);
        $searchform = $formSearch->handleRequest($request);  // je demande au formulaire de traiter la requête

        // dd($data);  je test ma requête et vérifie que je récupère bien mes éléments recherchés
        
        if($formSearch->isSubmitted() && $formSearch->isValid()){
            $searchedProducts = $productRepository->findSearch($searchform->get('search')->getData()
        );
        }
    }
}