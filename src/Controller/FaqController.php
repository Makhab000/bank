<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;

class FaqController extends AbstractController
{
    /**
     * @Route("/faq", name="faq")
     */
    public function index(ArticleRepository $repo)
    {

        $articles = $repo->findAll();

        return $this->render('faq/index.html.twig', [
            'controller_name' => 'FaqController',
            'articles' => $articles
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('faq/home.html.twig', [
            'title'=> "Bienvenue sur Bank.com",
            'age'=> 31
        ]);
    }
    /**
     * @Route("/faq/new", name="faq_create")
     * @Route("/faq/{id}/edit", name="faq_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager) {
        if(!$article){
            $article = new Article();
        }

    //$form = $this->createFormBuilder($article)
    //                   ->add('title')
    //                 ->add('content')
    //               ->add('image')
    //             ->getForm();

        $form = $this->createForm(ArticleType::class,$article);

        $form->handleRequest($request);      
        
        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('faq_show', ['id' => $article->getId
            ()]);
        }

        return $this->render('faq/create.html.twig', [
            'formArticle'=> $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }
    /**
     * @Route("/faq/{id}", name="faq_show")
     */
    public function show(Article $article)
    {

        return $this->render('faq/show.html.twig', [
            'article' => $article
            
        ]);
    }
    
}
