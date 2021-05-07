<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use App\Classe\Mail;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', 'Thanks for contacting us. We will answer you as soon as possible');
            
            $mail = new Mail();
            $mail->send('nhomadztest@gmail.com', 'Nhomadz', 'You have received a new message', "Hello ");
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
