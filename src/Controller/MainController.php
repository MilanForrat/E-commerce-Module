<?php

namespace App\Controller;

use App\Form\SearchForm;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProductRepository $productRepository, Request $request)
    {
        $products = $productRepository->findBy(['status' => 1]);

        $form = $this->createForm(SearchForm::class);
        $searchform = $form->handleRequest($request);  // je demande au formulaire de traiter la requête

        // dd($data);  je test ma requête et vérifie que je récupère bien mes éléments recherchés
        
        if($form->isSubmitted() && $form->isValid()){
            $searchedProducts = $productRepository->findSearch($searchform->get('search')->getData()
        );
        }

        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }
}
