<?php

namespace App\Controller;


use App\Entity\Membre;
use App\Form\InscriptionType;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionController extends AbstractController{

    #[Route("/inscription" , name:"app_inscription")]
    public function inscription(
            Request $request , 
            ManagerRegistry $doctrine , 
            UserPasswordHasherInterface $hasher,
            MailerInterface $mailer 
            ) : Response{

        // décrire formulaire 
        $membre = new Membre();
        $form =  $this->createForm(InscriptionType::class, $membre);

        // remplir les champs du formulaire avec des infos
        $form->handleRequest($request);
        //dd($membre);

        if($form->isSubmitted() && $form->isValid()){

            
            $email = (new Email())->from("no-response@h3hitema.com")
                 ->to($membre->getEmail())
                 ->replyTo('no-response@example.com')
                 ->subject("inscription site jour1 demo")
                 ->text("votre profil est désormais enregistré dans le site de demo") 
                 ->html("<h1>Bravo</h1><p>votre profil est désormais enregistré dans le site de demo</p>");

            //dd($mailer);

            $email = new TemplatedEmail();
                $email->from("malik.h@webdevpro.net")
                      ->to($membre->getEmail())
                      ->subject("tester 3rd party SMTP : Sending Blue")
                      ->htmlTemplate("emails/first.html.twig")
                      ->context([
                        "to" => $membre->getEmail() ,
                        "message" => "un message"
                      ]);
                      
                $mailer->send($email);

            $mailer->send($email);



            $em = $doctrine->getManager(); 
            $passwordHashe = $hasher->hashPassword($membre, $membre->getPassword());
            $membre->setPassword($passwordHashe);
            $membre->setRoles(['ROLE_USER']); 
            $em->persist($membre);
            $em->flush();

            //dd($membre->getEmail());

            /** ajouter l'émission d'email */
            

            //dump($result);

            return $this->redirectToRoute("connexion");
        }

        return $this->render("inscription/inscription.html.twig" , [
            // envoie le formulaire à la vue pour qu'il s'affiche
            "form" => $form->createView()
        ]);
        // créer le formulaire
        // récupérer des valeurs $_POST 
        // verifier que les valeurs en $_POST sont conformes
        // si ok => super 
        // sinon afficher le formulaire avec des messages d'erreur
    }
}

