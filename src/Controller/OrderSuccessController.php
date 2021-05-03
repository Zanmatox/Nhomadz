<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Classe\Cart;
use App\Classe\Mail;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/order/success/{stripeSessionId}", name="order_validate")
     */
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if (!$order->getIsPaid()) {
            // Vider la session "cart"
            $cart->remove();

            // Modifier le statut isPaid de ma commande en mettant 1
            $order->setIsPaid(1);
            $this->entityManager->flush();
            
            $mail = new Mail();
            $content = "Hello ".$order->getUser()->getFirstname()."<br/>Welcome to your favorite shop.<br><br/>";
            $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(), 'Thank you for your order on Nhomadz', $content);

        }
        
        return $this->render('order_success/index.html.twig',[
            'order' => $order
        ]);
    }
}
