<?php

namespace App\Controller\Admin;

use App\Entity\Marque;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MarqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Marque::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            DateTimeField::new('createdAt'),
        ];
    }
    
}
