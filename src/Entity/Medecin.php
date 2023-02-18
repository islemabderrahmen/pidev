<?php

namespace App\Entity;

use App\Repository\MedecinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Nullable;

#[ORM\Entity(repositoryClass: MedecinRepository::class)]
class Medecin extends User
{
   
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_cabinet = null;

    #[ORM\Column(length: 255)]
    private ?string $fixe = null;

    #[ORM\Column(length: 255)]
    private ?string $diplome_formation = null;

    #[ORM\Column]
    private ?float $tarif = null;

    #[ORM\Column]
    private ?bool $cnam = null;

   
    

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\ManyToOne(inversedBy: 'medecin')]
    private ?Specialites $specialites = null;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Consulation::class)]
    private Collection $consulations;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: RendezVous::class)]
    private Collection $rendezVouses;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Images::class)]
    private Collection $imagesCabinet;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Dossier::class)]
    private Collection $dossiers;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->consulations = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
        $this->imagesCabinet = new ArrayCollection();
        $this->dossiers = new ArrayCollection();
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAdresseCabinet(): ?string
    {
        return $this->adresse_cabinet;
    }

    public function setAdresseCabinet(string $adresse_cabinet): self
    {
        $this->adresse_cabinet = $adresse_cabinet;

        return $this;
    }

    public function getFixe(): ?string
    {
        return $this->fixe;
    }

    public function setFixe(string $fixe): self
    {
        $this->fixe = $fixe;

        return $this;
    }

    public function getDiplomeFormation(): ?string
    {
        return $this->diplome_formation;
    }

    public function setDiplomeFormation(string $diplome_formation): self
    {
        $this->diplome_formation = $diplome_formation;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function isCnam(): ?bool
    {
        return $this->cnam;
    }

    public function setCnam(bool $cnam): self
    {
        $this->cnam = $cnam;

        return $this;
    }

    
   

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setMedecin($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getMedecin() === $this) {
                $article->setMedecin(null);
            }
        }

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
     * @return Collection<int, Consulation>
     */
    public function getConsulations(): Collection
    {
        return $this->consulations;
    }

    public function addConsulation(Consulation $consulation): self
    {
        if (!$this->consulations->contains($consulation)) {
            $this->consulations->add($consulation);
            $consulation->setMedecin($this);
        }

        return $this;
    }

    public function removeConsulation(Consulation $consulation): self
    {
        if ($this->consulations->removeElement($consulation)) {
            // set the owning side to null (unless already changed)
            if ($consulation->getMedecin() === $this) {
                $consulation->setMedecin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): self
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setMedecin($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): self
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getMedecin() === $this) {
                $rendezVouse->setMedecin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImagesCabinet(): Collection
    {
        return $this->imagesCabinet;
    }

    public function addImagesCabinet(Images $imagesCabinet): self
    {
        if (!$this->imagesCabinet->contains($imagesCabinet)) {
            $this->imagesCabinet->add($imagesCabinet);
            $imagesCabinet->setMedecin($this);
        }

        return $this;
    }

    public function removeImagesCabinet(Images $imagesCabinet): self
    {
        if ($this->imagesCabinet->removeElement($imagesCabinet)) {
            // set the owning side to null (unless already changed)
            if ($imagesCabinet->getMedecin() === $this) {
                $imagesCabinet->setMedecin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dossier>
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossier $dossier): self
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers->add($dossier);
            $dossier->setMedecin($this);
        }

        return $this;
    }
    

    public function removeDossier(Dossier $dossier): self
    {
        if ($this->dossiers->removeElement($dossier)) {
            // set the owning side to null (unless already changed)
            if ($dossier->getMedecin() === $this) {
                $dossier->setMedecin(null);
            }
        }

        return $this;
    }
 
}
