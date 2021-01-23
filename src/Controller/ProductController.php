<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchForm;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/", name="liste")
     * @return void
     */
    public function index(ProductRepository $repository, Request $request){

        //on définit le nombre d'éléments par page
        $limit = 10;

        // le int pour être sûr de convertir en int mon numéro de page et le 1 est la valeur par défaut et Request pour récupérer la requête passée par le submit du formulaire
        $page = (int)$request->query->get("page", 1);  
        
        // je vérifie si j'obtiens bien un 1 (je peux tester de mettre un autre numéro après le /?page= et récupérer ce numéro)
        //dd($request); 

        $paginatedProducts = $repository->getPaginatedProducts($page, $limit);

        // on récupère le nombre total de produits
        $total = $repository->getTotalProducts();
        //dd($total); // on test le nombre total de produit

        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);  // je demande au formulaire de traiter la requête
        // dd($data);  je test ma requête et vérifie que je récupère bien mes éléments recherchés
        $products = $repository->findSearch($data);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'paginatedProducts' => $paginatedProducts,
            'form' => $form->createView(),
            'total' => $total,        // nécessaire pour paginer
            'limit' => $limit,         // nécessaire pour paginer
            'page' => $page,            // nécessaire pour paginer
        ]);
    }


    /**
     * @Route("/search", name="product")
     */
    /*
    public function search(ProductRepository $repository, Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);  // je demande au formulaire de traiter la requête
        // dd($data);  je test ma requête et vérifie que je récupère bien mes éléments recherchés
        $products = $repository->findSearch($data);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }*/
}