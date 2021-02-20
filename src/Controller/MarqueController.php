<?php

namespace App\Controller;

use App\Form\SearchForm;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/marques", name="marques_")
 * @package App\Controller
 */
class MarqueController extends AbstractController
{
    /**
     * @Route("/marques", name="liste")
     */
    public function index(ProductRepository $repository, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request): Response
    {
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        $products = $repository->findBy(['status' => 1]);

        return $this->render('marque/index.html.twig', [
            'products' => $products, 
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function marquesDetails($id, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository, Request $request){

        $products = $productRepository->findAll();
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        $marqueById = $marqueRepository->viewById($id);


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
        //dump($marqueById);
        
        return $this->render('marque/details.html.twig', [
            'marqueById' => $marqueById,
            'formSearch' => $formSearch->createView(),
            'marques' => $marques,
            'categories' => $categories,
        ]);


    }
}
