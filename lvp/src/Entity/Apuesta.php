<?php

namespace App\Entity;

use App\Repository\ApuestaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=ApuestaRepository::class)
 */
class Apuesta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"apuesta"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Apostador::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"apuesta"})
     * @MaxDepth(1)
     * @Assert\Valid
     */
    private $apostador;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"apuesta"})
     * @Assert\PositiveOrZero(
     *     message = "Teemo, deja de feedear"
     * )
     */
    private $kills;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApostador(): ?Apostador
    {
        return $this->apostador;
    }

    public function setApostador(Apostador $apostador): self
    {
        $this->apostador = $apostador;

        return $this;
    }

    public function getKills(): ?int
    {
        return $this->kills;
    }

    public function setKills(int $kills): self
    {
        $this->kills = $kills;

        return $this;
    }
}
