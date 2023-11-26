<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccueilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // // ->add('id_commande')
            // ->add('id_membre')
            // ->add('id_vehicule')
            ->add('adresse_depart' , TextType::class , ["label" => "Adresse de départ"])
            ->add('date_heure_depart', DateType::class , ["label" => "Début de location"])
            ->add('date_heure_fin', DateType::class , ["label" => "Fin de location"])
            // ->add('prix_total')
            // ->add('date_enregistrement')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
