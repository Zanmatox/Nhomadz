<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\OrderType;
use App\Classe\Cart;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    { 
        if (!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('account_address_add');
        }
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getuser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form ->createView(),
            'cart' => $cart->getFull()
        ]);
    }
    
}
