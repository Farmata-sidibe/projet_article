<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\Persistence\ManagerRegistry;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $user->setFirstname($user->getFirstname());
            $user->setEmail($user->getEmail());
            $user->setPassword($user->getPassword());

            $em->persist($user);
            $em->flush();

            $session = $request->getSession();
            $session->set('notification', 'Bravo! Vous étes bien inscrit');
            $session->set('type_notif', 'alert-success');
            
            return $this->redirectToRoute('list_article');
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}