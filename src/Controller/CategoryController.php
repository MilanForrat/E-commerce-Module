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
     * @Route("/details/{id}", name="details", methods={"GET"}, requirements={"id"="\d+"}))
     */
    public function categoriesDetails($id, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request){
        $marques = $marqueRepository->findAll();
        $categories= $categoryRepository->findAll();
        $categoryById = $categoryRepository->find($id);

        //dump($categoryById);
        
        return $this->render('category/details.html.twig', [
            'categoryById' => $categoryById,
            'marques' => $marques,
            'categories' => $categories,
        ]);
    }
}
