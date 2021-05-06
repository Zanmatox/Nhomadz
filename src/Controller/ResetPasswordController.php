<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\ResetPassword;
use \Datetime;
use App\Classe\Mail;
use App\Form\ResetPasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/forgot_password", name="reset_password")
     */
    public function index(Request $request): Response
    {   
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($request->get('email'));
            
            if ($user) {
                // 1 : Enregistrer en base la demande de reset_password avec user, token, createdAt
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // 2 : Envoyer email a l'user avec lien permettant de mettre a jour son mdp
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);
                $content = "Hello ".$user->getFirstname().
                "<br/>You have requested to reset your password on Nhomadz.<br/><br/>";
                $content .="Please click on the following link to <a href='".$url."'>update your password</a>.";
                $mail = new Mail();
                $mail->send($user->getEmail(), 
                $user->getFirstName().' '.
                $user->getLastName(), 'Reset your password on nhomadz.',
                $content);
                $this->addFlash('notice', 'You have received an email. Please follow the instructions to reset your password.');
            } else {
                $this->addFlash('notice', 'This email address is not in our database');

            }
        }
        return $this->render('reset_password/index.html.twig');
    }
    
    /**
     * @Route("/change_my_password/{token}", name="update_password")
     */
    public function update(Request $request, $token, UserPasswordEncoderInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if(!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }
        // Vérifier le createdAt = now - 3h 
        $now = new \DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 1 hour')) {
            $this->addFlash('notice', 'Your rest password link has expired. Please do it again.');
            return $this->redirectToRoute('reset_password');
        }
        // Rendre une vue avec mot de passe et confirmer votre mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();
            
            // Encodage des mots de passe
            $password = $encoder->encodePassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);
            
            // Flush en base de donnée
            $this->entityManager->flush();
            
            // Redirection de l'user vers la page de connexion
            $this->addFlash('notice', 'Your password has been updated.');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
