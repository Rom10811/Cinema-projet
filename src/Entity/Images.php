<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 */
class Images
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\OneToOne(targetEntity=Film::class, inversedBy="images", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdFilm;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getIdFilm(): ?Film
    {
        return $this->IdFilm;
    }

    public function setIdFilm(Film $IdFilm): self
    {
        $this->IdFilm = $IdFilm;

        return $this;
    }
}
