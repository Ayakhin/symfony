<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie/new', name: 'categorie_new')]
    public function index(Request $request , ManagerRegistry $doctrine): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class , $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            // message flash 
            // apparitre uniquement une fois suite à la validation du formulaire
            // utilise la $_SESSION
            $this->addFlash("success" , "La catégorie a bien été enregistrée en base de données");
            return $this->redirectToRoute("home");
        }
        return $this->render('categorie/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/categorie/list" , name:"categorie_list")]
    public function listeCategorie(CategorieRepository $repo) :Response{

        $categories = $repo->findAll();

        //dd($repo->findByIdAndLabelActive(true));

        return $this->render("categorie/liste.html.twig" , ["categories" => $categories]);

    }
}
