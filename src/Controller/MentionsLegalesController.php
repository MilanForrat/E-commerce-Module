<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsLegalesController extends AbstractController
{
    /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function index(CategoryRepository $categoryRepository, MarqueRepository $marqueRepository, Request $request): Response
    {
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('mentions_legales/index.html.twig', [
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }
}
