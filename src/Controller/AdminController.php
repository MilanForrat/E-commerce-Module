<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryFormType;
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


        return $this->render('admin/category/ajout.html.twig', [
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


        return $this->render('admin/product/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

