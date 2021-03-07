<?php

namespace App\Controller;

use App\Data\Cart;
use App\Form\SearchFormType;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cartComplete, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository, Request $request): Response
    {
        //je débug et je check ce qui se trouve dans mon panier 
        //dd($cart->get());
     


        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        $formSearch = $this->createForm(SearchFormType::class);
        $searchRequest = $formSearch->handleRequest($request);  // je demande au formulaire de traiter la requête
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
    
     
        return $this->render('cart/index.html.twig', [
            'categories' => $categories,
            'marques' => $marques,
            'formSearch' => $formSearch->createView(),
            'cart' => $cartComplete->getCartInfos(),
        ]);
    }

    /**
     * Method to add an article to the cart (using the App\Data\Cart class)
     * @Route("/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * Method to remove the whole cart (not just an article)
     * @Route("/cart/remove", name="remove_my_cart")
     */
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('products_liste');
    }

    /**
     * Method to remove an article
     * @Route("/cart/delete/{id}", name="delete_to_cart")
     */
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * Method to decrease quantity of an article
     * @Route("/cart/decrease/{id}", name="decrease_to_cart")
     */
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}
