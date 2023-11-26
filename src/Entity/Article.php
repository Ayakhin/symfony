<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// ORM Object Relation Mapper 
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    public const NOM_AUTEUR = ["Victor Hugo" , "moi"];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 200)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min : 3,
        max : 255,
        minMessage:"Titre doit contenir au minimum {{ limit }} caractères",
        maxMessage:"Titre doit contenir au maximum {{ limit }} caractères",
    )]
    private ?string $title = null;

    #[Assert\Choice(
        choices : self::NOM_AUTEUR,
        message : "choisir un nom d'auteur valide"
    )]
    #[ORM\Column(length: 200)]
    private ?string $auteur = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[Assert\Length(
        min : 3,
        max : 5000,
        minMessage:"La description doit contenir au minimum {{ limit }} caractères",
        maxMessage:"La description doit contenir au maximum {{ limit }} caractères",
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Range(
        min : 2 ,
        max :10 ,
        notInRangeMessage : "le nombre de like doit être compris entre {{ min }} et {{ max }}"
    )]
    private ?int $liked = null;

    #[ORM\ManyToOne( inversedBy: 'article')]
    private ?Categorie $categories  = null ;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Membre $membre = null;

    public function __construct()
    {
        // dès que l'utilise utilise le formulaire 
        // on donne une valeur par défaut à la propriété created_at
        $this->setCreatedAt(new  \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }


    public function getLiked(): ?int
    {
        return $this->liked;
    }

    public function setLiked(?int $liked): static
    {
        $this->liked = $liked;

        return $this;
    }

 
    public function getCategories(): ?Categorie
    {
        return $this->categories;
    }

    public function setCategories(?Categorie $categorie): static
    {
        $this->categories = $categorie;

        return $this;
    }

    public function getImage(): ?string
    {
        // return $this->image ?? "https://via.placeholder.com/400x200" ;
        return $this->image === null ? "https://via.placeholder.com/400x200" : "upload/".$this->image ;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): static
    {
        $this->membre = $membre;

        return $this;
    }

}
