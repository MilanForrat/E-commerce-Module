<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\SearchForm;
use App\Form\UserModifyFormType;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository, Request $request): Response
    {
        $products = $productRepository->findBy(['status' => 1]);
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

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

        return $this->render('user/index.html.twig', [
        'formSearch' => $formSearch->createView(),
        'categories' => $categories,
        'marques' => $marques,
        ]);
    }
    /**
     * @Route("/user/modify", name="app_user_modify_account")
     */
    public function modifyUserAccount(Request $request, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository): Response
    {
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findBy(['status' => 1]);

        $user = $this->getUser();
        $form = $this->createForm(UserModifyFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('app_user');
        }

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

        return $this->render('user/modify.html.twig', [
            'form' => $form->createView(),
            'formSearch' => $formSearch->createView(),
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    
}
