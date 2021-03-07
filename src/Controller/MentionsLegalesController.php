<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsLegalesController extends AbstractController
{
    /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function index(CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository,Request $request): Response
    {
        $products = $productRepository->findAll();
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        $formSearch = $this->createForm(SearchFormType::class);
        $searchRequest = $formSearch->handleRequest($request);

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

        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('mentions_legales/index.html.twig', [
            'categories' => $categories,
            'formSearch' => $formSearch->createView(),
            'marques' => $marques,
        ]);
    }
}
