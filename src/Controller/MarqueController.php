<?php

namespace App\Controller;

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
}
