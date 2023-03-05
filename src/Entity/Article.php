<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('articles')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 9,
        max: 50,
        minMessage: 'le nom doit etre plus que {{ limit }} caractéres',
        maxMessage: 'le nom doit etre inferieur de {{ limit }} caractéres',
    )]
    
   #[Assert\NotBlank(message: 'Le nom  ne peut pas être vide')]
   #[Groups('articles')]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide')]
    #[Assert\Length(
        min: 20,
        max: 2000,
        minMessage: 'votre text doit etre plus que {{ limit }} caractéres',
        maxMessage: 'votre text doit etre inferieur de {{ limit }} caractéres',
    )]
    #[Groups('articles')]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Medecin $medecin = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Images::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $images;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Specialites $specialites = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleLike::class)]
    private Collection $articleLikes;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleFavorie::class)]
    private Collection $articleFavories;


  

  

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->date= new \DateTime();
        $this->articleLikes = new ArrayCollection();
        $this->articleFavories = new ArrayCollection();
       
       
        
    
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getMedecin(): ?Medecin
    {
        return $this->medecin;
    }

    public function setMedecin(?Medecin $medecin): self
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }

        return $this;
    }


    public function __toString(): string{
        return (string)$this->nom;
       }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setArticle($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSpecialites(): ?Specialites
    {
        return $this->specialites;
    }

    public function setSpecialites(?Specialites $specialites): self
    {
        $this->specialites = $specialites;

        return $this;
    }

    /**
     * @return Collection<int, ArticleLike>
     */
    public function getArticleLikes(): Collection
    {
        return $this->articleLikes;
    }

    public function addArticleLike(ArticleLike $articleLike): self
    {
        if (!$this->articleLikes->contains($articleLike)) {
            $this->articleLikes->add($articleLike);
            $articleLike->setArticle($this);
        }

        return $this;
    }

    public function removeArticleLike(ArticleLike $articleLike): self
    {
        if ($this->articleLikes->removeElement($articleLike)) {
            // set the owning side to null (unless already changed)
            if ($articleLike->getArticle() === $this) {
                $articleLike->setArticle(null);
            }
        }

        return $this;
    }
    public function getLikesCount(): int
    {
        return $this->articleLikes->count();
    }

    /**
     * @return Collection<int, ArticleFavorie>
     */
    public function getArticleFavories(): Collection
    {
        return $this->articleFavories;
    }

    public function addArticleFavory(ArticleFavorie $articleFavory): self
    {
        if (!$this->articleFavories->contains($articleFavory)) {
            $this->articleFavories->add($articleFavory);
            $articleFavory->setArticle($this);
        }

        return $this;
    }

    public function removeArticleFavory(ArticleFavorie $articleFavory): self
    {
        if ($this->articleFavories->removeElement($articleFavory)) {
            // set the owning side to null (unless already changed)
            if ($articleFavory->getArticle() === $this) {
                $articleFavory->setArticle(null);
            }
        }

        return $this;
    }
}
    
   

   

   

    

    
   

    

