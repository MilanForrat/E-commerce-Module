<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Marque;
use App\Entity\Product;
use App\Form\CategoryFormType;
use App\Form\MarqueFormType;
use App\Form\ProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            
        ]);
    }

     /**
     * @Route("/category/add", name="category_add")
     */
    public function addCategory(Request $request): Response
    {

        $category = New Category;

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }


        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/product/add", name="product_add")
     */
    public function addProduct(Request $request): Response
    {

        $product = New Product;

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }


        return $this->render('admin/product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

         /**
     * @Route("/marque/add", name="marque_add")
     */
    public function addMarque(Request $request): Response
    {

        $marque = New Marque;

        $form = $this->createForm(MarqueFormType::class, $marque);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($marque);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/marque/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

