<?php

namespace App\Controller;

use App\Form\SearchForm;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy(['status' => 1]);
        $productsMain = $productRepository->findTopEight();

        return $this->render('main/index.html.twig', [
            'productsMain' => $productsMain,
        ]);
    }
}
