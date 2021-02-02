<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstname'),
            TextField::new('lastname'),
            EmailField::new('email'),
            ArrayField::new('roles'),
            BooleanField::new('isVerified'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt')->hideOnIndex(),
            TextField::new('phoneNumber')->hideOnIndex(),
            TextField::new('roadNumber')->hideOnIndex(),
            TextField::new('road')->hideOnIndex(),
            TextField::new('city')->hideOnIndex(),
            TextField::new('postalCode')->hideOnIndex(),
            TextareaField::new('receiptAddress')->hideOnIndex(),
        ];
    }
    
}
