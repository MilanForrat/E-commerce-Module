<?php

namespace App\Controller;

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

        $form = $this->createForm(SearchForm::class);
        $searchform = $form->handleRequest($request);  // je demande au formulaire de traiter la requête
        
        if($form->isSubmitted() && $form->isValid()){
            $searchedProducts = $repository->findSearch($searchform->get('search')->getData()
        );
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
            'products' => $products, 
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

}