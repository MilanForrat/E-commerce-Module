<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\SearchFormType;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAddressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/user/address", name="user_address")
     */
    public function index(CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository, Request $request): Response
    {

        $products = $productRepository->findBy(['status' => 1]);
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

        return $this->render('user/address.html.twig', [
            'formSearch' => $formSearch->createView(),
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    /**
     * @Route("/user/add-address", name="user_address_add")
     */
    public function add(CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository, Request $request)
    {

        $products = $productRepository->findBy(['status' => 1]);
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        $formSearch = $this->createForm(SearchFormType::class);
        $searchRequest = $formSearch->handleRequest($request);

        $address = new Address();
        $formAddress = $this->createForm(AddressType::class, $address);
        $formAddress->handleRequest($request);

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

        if($formAddress->isSubmitted() && $formAddress->isValid()){
            // je set mon User en appelant getUser
            $address->setUser($this->getUser()); 

            // j'enregistre ma data (persist = je fige la data)
            $this->entityManager->persist($address);

            // j'envoie la data en BDD (flush)
            $this->entityManager->flush();

            return $this->redirectToRoute('user_address');
        }

        return $this->render('user/form-address.html.twig', [
            'formSearch' => $formSearch->createView(),
            'formAddress' => $formAddress->createView(),
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    /**
     * @Route("/user/modify-address/{id}", name="user_address_modify")
     */
    public function modify(CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, ProductRepository $productRepository, Request $request, $id)
    {

        $products = $productRepository->findBy(['status' => 1]);
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        $formSearch = $this->createForm(SearchFormType::class);
        $searchRequest = $formSearch->handleRequest($request);

        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        // je vérifie deux éléments : qu'il existe bien une adresse et que l'adresse à modifier correspond bien à celle de l'utilisateur connecté
        if(!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('user_address');
        }

        $formAddress = $this->createForm(AddressType::class, $address);
        $formAddress->handleRequest($request);

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

        if($formAddress->isSubmitted() && $formAddress->isValid()){
            // j'envoie la data en BDD (flush)
            $this->entityManager->flush();

            return $this->redirectToRoute('user_address');
        }

        return $this->render('user/form-address.html.twig', [
            'formSearch' => $formSearch->createView(),
            'formAddress' => $formAddress->createView(),
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    /**
     * @Route("/user/delete-address/{id}", name="user_address_delete")
     */
    public function delete($id)
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        // je vérifie deux éléments : que j'ai bien une adresse et que j'en suis le propriétaire
        if($address && $address->getUser() == $this->getUser()){
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        

        return $this->redirectToRoute('user_address');
    }
}

