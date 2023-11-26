<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    private $repo ;

    public function __construct(ArticleRepository $repo)
    {
        $this->repo =  $repo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title') 
            ->add('description')
            ->add('auteur')
            ->add('liked')
            ->add("image" , FileType::class , [
                "mapped" => false ,
                "label" => "image à la une"
            ])
            ->add("categories" , EntityType::class , [
                'class' => Categorie::class,
                'choice_label' => 'label',
                'label' => 'catégorie',
                'placeholder' => "choisir une catégorie"
            ]);
        // liste des champs du formulaire 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class, 
        ]);
    }
}
