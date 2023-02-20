<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\SpecialitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(ArticleRepository  $articleRepository): Response
    {
        return $this->render('home.html.twig', [

            'controller_name' => 'FrontController',
            'articles' => $articleRepository->findAll(),

        ]);
    }

    #[Route('/blog', name: 'app_blog')]
    public function blog(ArticleRepository $articleRepository , SpecialitesRepository $specialitiesRepository): Response
    {
        return $this->render('front/article.html.twig', [
            'articles' => $articleRepository->findAll(),
            'specialities'=>$specialitiesRepository->findALL(),

        ]);
    }

    #[Route('/blogDetails/{id}', name: 'app_blog_details' , methods: ['GET', 'POST'])]
    public function blogDetails(ArticleRepository $articleRepository,SpecialitesRepository $specialitiesRepository,$id,Article $article,Request $request,CommentaireRepository $commentaireRepository): Response
    {     
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        $commentaireRepository->findByArticle($article);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $commentaire->setArticle($article);
            $commentaire->setDate(new \DateTime());
           $em->persist($commentaire);
           $em->flush();
            return $this->redirectToRoute('app_blog_details', ['id' => $id]);
            dump($commentaire);
        }
        return $this->renderForm('front/blog-details.html.twig', [
            'articles' => $articleRepository->findById($id),
            'article'=>$articleRepository->findBy([],['id'=>'desc']),
            'commentaires'=>$commentaireRepository->findByArticle($article),
            'specialities'=>$specialitiesRepository->findALL(),
            //'specialities'=>$articleRepository->findBySpecialites($id),
            'form' => $form,

        ]);
    }
}
