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
 * @Route("/categories", name="categories_")
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="liste")
     */
    public function index(CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request): Response
    {
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function categoriesDetails($id, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository, Request $request){
      
        $products = $productRepository->findAll();
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        $categoryById = $categoryRepository->viewById($id);
        $countCategories = $categoryRepository->getTotalCategories();
        $associatedProducts = $productRepository->getAssociatedProducts(6, $id);


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
        //dump($categoryById);
        //dump($associatedProducts);
        //dump($id);
        
        return $this->render('category/details.html.twig', [
            'categoryById' => $categoryById,
            'formSearch' => $formSearch->createView(),
            'marques' => $marques,
            'categories' => $categories,
            'countCategories' => $countCategories,
            'associatedProducts' => $associatedProducts,
        ]);
    }
}
