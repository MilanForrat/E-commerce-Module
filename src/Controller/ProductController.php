<?php

namespace App\Controller;

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

        $products = $repository->findAll();

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

        
        $form = $this->createForm(SearchForm::class);
        $searchform = $form->handleRequest($request);  // je demande au formulaire de traiter la requête
        // dd($data);  je test ma requête et vérifie que je récupère bien mes éléments recherchés
        if($form->isSubmitted() && $form->isValid()){
            $products = $repository->findSearch($searchform->get('search')->getData()
        );
        }
        
        //dump($products);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'paginatedProducts' => $paginatedProducts,
            'form' => $form->createView(),
            'total' => $total,        // nécessaire pour paginer
            'limit' => $limit,         // nécessaire pour paginer
            'page' => $page,            // nécessaire pour paginer
        ]);
    }

}