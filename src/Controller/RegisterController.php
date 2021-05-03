<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Classe\Mail;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;

    }
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            
            if (!$search_email)
            {
                $password = $encoder->encodePassword($user,$user->getPassword());

                $user->setPassword($password);
    
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                
                $mail = new Mail();
                $content = "Hello ".$user->getFirstname()."<br/>Welcome to your favorite shop.<br><br/>";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Welcome to Nhomadz', $content);

                $notification = "You have been registered. You can now log in your account.";
    
            } else {
                $notification = "The email you have entered is already existing in our database.";
            }
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification

        ]);
    }
}
