<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Marque;
use App\Entity\Product;
use App\Entity\Transporter;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('E-CommerceModule Admin Pannel');
    }

    public function configureMenuItems(): iterable
    {
        return [
        MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

        MenuItem::section('Utilisateurs'),
        MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class),

        MenuItem::section('Produits'),
        MenuItem::linkToCrud('Produits', 'fas fa-cube', Product::class),
        MenuItem::linkToCrud('Catégories', 'far fa-folder-open', Category::class),
        MenuItem::linkToCrud('Marques', 'fa fa-tags', Marque::class),
        MenuItem::linkToCrud('Transporteurs', 'fa fa-tags', Transporter::class),

        MenuItem::section('Quitter'),
        MenuItem::linkToLogout('Déconnexion', 'fas fa-sign-out-alt'),
    ];
    }
}
