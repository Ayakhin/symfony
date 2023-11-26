<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\AccueilType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ConnexionController extends AbstractController{
    public function connexion(AuthenticationUtils $authenticationUtils):Response
    {

        // $commande = new Commande ();
        // $form = $this->createForm(AccueilType::class, $commande);

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this-> render("connexion/connexion.html.twig", ['last_username' => $lastUsername, 'error' => $error]);

    }
}

