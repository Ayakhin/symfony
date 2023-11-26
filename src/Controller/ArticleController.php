<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin")]
class ArticleController extends AbstractController
{

    private function moveAndUpdateImage($request , $article) :void{
        // récupérer l'image 
        $file = $request->files->get("article")["image"];
        if(!empty($file)){
            // déplacer dans le dossier upload
            // le dossier où stocker l'image 
            $uploadDirectory = $this->getParameter("upload_directory");
            $nomFichier = md5(uniqid()) . "." . $file->guessExtension();
            
            $file->move($uploadDirectory, $nomFichier);
            
            // enregistrer en base de données 
            // url relatif de l'image 
            $article->setImage($nomFichier);
        }
    }


    #[Route('/article/new', name: 'article_new')]
    public function index( Request $request , ManagerRegistry $doctrine ): Response
    {
        // générer le formulaire depuis ArticleType 
        $article = new Article(); // {id = null} INSERT 
        dump($article);
        $article->setAuteur("Victor Hugo");
        //$article->set
        $form = $this->createForm(ArticleType::class , $article);
        // et le retourner 

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->moveAndUpdateImage($request , $article);

            $em = $doctrine->getManager();
            // dd($article);
            $em->persist($article); // va stocker les valeurs du formulaire 
            // DANS l'entité
            $em->flush();
            $this->addFlash("success", "Article bien crée");
            return $this->redirectToRoute("home");
        }

        return $this->render('article/index.html.twig', [
            "form" => $form->createView() ,
            "title" => "créer un article" ,
            "btn" => "créer"
        ]);
    }

    #[Route("/article/list" , name:"article_list")]
    public function listeArticles(ArticleRepository $repo) :Response{

        $articles = $repo->getArticleDesc();

        return $this->render( "article/liste.html.twig" , [
            "articles" => $articles 
        ] );

    }

    #[Route( "/article/update/{id}", name:"article_update")]
    public function updateArticle($id , ArticleRepository $repo , Request $request, ManagerRegistry $doctrine):Response{

        $article = $repo->find($id); // {id = 1 } => UPDATE
        dump($article);

        $form = $this->createForm(ArticleType::class , $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->moveAndUpdateImage($request , $article);

            $em = $doctrine->getManager();
            // dd($article);
            $em->persist($article); // va stocker les valeurs du formulaire 
            // DANS l'entité
            $em->flush();
            $this->addFlash("success", "Article bien crée");
            return $this->redirectToRoute("home");
        }

        return $this->render( "article/index.html.twig" , [
            "form" => $form ,
            "title" => "Mettre à jour un article",
            "btn" => "update"
        ] );
    }

    #[Route("/article/delete/{id}" , name:"article_delete")]
    public function deleteArticle($id , ArticleRepository $repo, ManagerRegistry $doctrine):Response{

        $article = $repo->find($id);

        $nomImage = $article->getImage();
        
        if($nomImage){
            $uploadDirectory = $this->getParameter("upload_directory");
            $uploadDirectory = str_replace("/upload", "" , $uploadDirectory);
            //dd($uploadDirectory);
            $filetodelete = "{$uploadDirectory}/{$nomImage}";
            if(file_exists($filetodelete)) unlink($filetodelete);
        }

        $em = $doctrine->getManager();
        $em->remove($article);
        $em->flush();
        $this->addFlash("success", "article $id a bien été supprimé");
        return $this->redirectToRoute("article_list");

    }

}

// cas pratique 
// créer CategorieType basé sur l'entité Categorie 
// créer un nouveau Controller permettant d'ajouter de nouvelle catégorie 
// dans la vue de l'action du controlleur => afficher un formulaire 
// qui va permettre d'ajouter une nouvelle catégorie 