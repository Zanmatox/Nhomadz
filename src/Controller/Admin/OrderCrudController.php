<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string 
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail');
    }
    
    public function configureCrud(Crud $crud): Crud 
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Made the'),
            TextField::new('user.getFullName', 'User'),
            MoneyField::new('total', 'Products price')->setCurrency('USD'),
            TextField::new('carrierName', 'Carrier'),
            MoneyField::new('carrierPrice', 'Carrier price')->setCurrency('USD'),
            BooleanField::new('isPaid', 'Paid'),
            ArrayField::new('orderDetails', 'Products bought')->hideOnIndex()
        ];
    }
    
}
