<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeanceRepository::class)
 */
class Seance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $heure;

    /**
     * @ORM\ManyToOne(targetEntity="Film")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="id_film_id", referencedColumnName="id")
     * })
     */
    private $idFilm;

    /**
     * @ORM\ManyToOne(targetEntity="Salle")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="id_salle_id", referencedColumnName="id")
     * })
     */
    private $idSalle;

    /**
     * @ORM\Column(type="integer")
     */
    private $PlacesRestantes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PlacesReserves;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeure(): ?\DateTimeImmutable
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeImmutable $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getIdFilm(): ?Film
    {
        return $this->idFilm;
    }

    public function setIdFilm(?Film $idFilm): self
    {
        $this->idFilm = $idFilm;

        return $this;
    }

    public function getIdSalle(): ?Salle
    {
        return $this->idSalle;
    }

    public function setIdSalle(?Salle $idSalle): self
    {
        $this->idSalle = $idSalle;

        return $this;
    }

    public function getPlacesRestantes(): ?int
    {
        return $this->PlacesRestantes;
    }

    public function setPlacesRestantes(int $PlacesRestantes): self
    {
        $this->PlacesRestantes = $PlacesRestantes;

        return $this;
    }

    public function getPlacesReserves(): ?int
    {
        return $this->PlacesReserves;
    }

    public function setPlacesReserves(?int $PlacesReserves): self
    {
        $this->PlacesReserves = $PlacesReserves;

        return $this;
    }

    public function __construct(){
        $this->PlacesReserves=0;
        $this->PlacesRestantes=40;
    }
}
