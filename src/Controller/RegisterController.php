<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        $session = $request->getSession();
        $session->set('notification', null);
        $session->set('type_notif', null);
        

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            $userExist = $em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if($userExist){
                $session = $request->getSession();
                $session->set('notification', "L'utilisateur éxiste déjà");
                $session->set('type_notif', "alert-danger");
            }else{
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword);

                $em->persist($user);
                $em->flush();

                $session = $request->getSession();
                $session->set('notification', "Utilisateur crée avec succès");
                $session->set('type_notif', "alert-success");                
            }

            return $this->redirectToRoute('list_article');
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
