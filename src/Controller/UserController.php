<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserModifyFormType;
use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(CategoryRepository $categoryRepository, MarqueRepository $marqueRepository): Response
    {
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('user/index.html.twig', [
        'categories' => $categories,
        'marques' => $marques,

        ]);
    }
    /**
     * @Route("/user/modify", name="app_user_modify_account")
     */
    public function modifyUserAccount(Request $request, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository): Response
    {
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

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

        return $this->render('user/modify.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
            'marques' => $marques,
        ]);
    }

    
}
