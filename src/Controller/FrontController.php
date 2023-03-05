<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleFavorie;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\SpecialitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function getArticlesInFavorites($idsSelectedArticles)
    {
        if ($this->getUser()) {

            $user = $this->getUser();

            $favorites = $this->getDoctrine()->getManager()->getRepository(ArticleFavorie::class)
            ->findBy(array('user' => $user));
            $ids = array_map(function ($entity) {
                return $entity->getArticle()->getId();
            }, $favorites); // get ids of dishes in favorite


        } else {

            $ids = array(); // if user is not AUTHENTICATED_REMEMBERED show ids are null
        }

        return $ids;
    }

    #[Route('/blog', name: 'app_blog' , methods:['GET'])]
    public function blog(Request $request,ArticleRepository $articleRepository ,PaginatorInterface $paginator, SpecialitesRepository $specialitiesRepository): Response
    {
        $article=$articleRepository->findAll();
        $specialities=$specialitiesRepository->findAll();
        
        $article = $paginator->paginate(
            $article, // Requête contenant les données à paginer 
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 //Nombre de résultats par page
        );
        $idsSelectedArticles = [];
        foreach($article as $art){
            $idsSelectedArticles[] = $art->getId();
        }

        $idsArticlesInFavorites = $this->getArticlesInFavorites($idsSelectedArticles);

        return $this->render('front/article.html.twig', [
            'articles' => $article,
            'specialities'=>$specialities,
            'idsArticlesInFavorites'=> $idsArticlesInFavorites
            

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
       // $count = $specialitiesRepository->article->count();
     

        return $this->renderForm('front/blog-details.html.twig', [
            'articles' => $articleRepository->findById($id),
            'article'=>$articleRepository->findBy([],['id'=>'desc']),
            'commentaires'=>$commentaireRepository->findByArticle($article),
            'specialities'=>$specialitiesRepository->findALL(),
            //'specialities'=>$articleRepository->findBySpecialites($id),
            'form' => $form,

        ]);
    }
     
    #[Route('/article/favories/add-delete/{id}', name: 'article_favorie')]
    public function articleFavories(Request $request, Article $article, ManagerRegistry $doctrine)
    {
        $entityManger = $doctrine->getManager();

        $user = $this->getUser();
        $articleFavoriesExiste = $entityManger->getRepository(ArticleFavorie::class)
        ->findOneBy(array('article'=> $article, 'user'=> $user));
        if($articleFavoriesExiste){
            $entityManger->remove($articleFavoriesExiste);
            $entityManger->flush();
        }
        else{
            $articleFavorie = new ArticleFavorie();
            $articleFavorie->setUser($user);
            $articleFavorie->setArticle($article);
            $entityManger->persist($articleFavorie);
            $entityManger->flush();
        }

       return new JsonResponse();
    }
    
}

