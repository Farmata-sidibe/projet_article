<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\ArticleType;


class ArticleController extends AbstractController
{
    #[Route('/', name: 'list_article')]
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
    public function viewArticle($id_article,ManagerRegistry $doctrine): Response
    {
        $en = $doctrine->getManager();
        $article = $en->getRepository(Article::class)->find($id_article);

        return $this->render('article/viewArticle.html.twig', [
            'article' => $article,
        ]);
       
    }
    #[Route('/article-add', name: 'add_article')]
    public function addArticle(Request $request,ManagerRegistry $doctrine): Response
    {
        $en = $doctrine->getManager();

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();

            $article->setBody(nl2br($article->getBody()));

            $en->persist($article);
            $en->flush();

            return $this->redirectToRoute('list_article');
        }

        return $this->render('article/addArticle.html.twig', [
            'form' => $form->createView()
        ]);
       
    }
  
  
}
