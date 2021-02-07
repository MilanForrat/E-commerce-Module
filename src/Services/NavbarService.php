<?php

namespace App\Services;

use App\Repository\CategoryRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;

class NavbarService {
    public function loadNavbar(ProductRepository $productRepository, CategoryRepository $categoryRepository, MarqueRepository $marqueRepository){
        $navbarService= [];
        $products = $productRepository->findBy(['status' => 1]);
        $marques = $marqueRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $navbarService = [$products, $categories, $marques]
        ;
    }

}