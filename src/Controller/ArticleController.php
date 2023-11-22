<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $en = $doctrine->getManager();

        $articles = $en->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/edit/{id_article}', name: 'edit_article')]
    public function editArticle($id_article): Response
    {
       return $this->render('article/index.html.twig');
    }

    #[Route('/article/{id_article}', name: 'view_article')]
    public function viewArticle($id_article): Response
    {
        return $this->render('article/index.html.twig');
       
    }
  
  
}
