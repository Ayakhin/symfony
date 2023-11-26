<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\AccueilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AccueilController extends AbstractController{
    public function index():Response{

        $commande = new Commande ();
        $form = $this->createForm(AccueilType::class, $commande);
        return $this-> render("home/accueil.html.twig",[
            'form' => $form->createView()
        ]);

    }
}

