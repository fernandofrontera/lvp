<?php

namespace App\Entity;

use App\Repository\ApostadorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ApostadorRepository::class)
 * @ORM\Table(name="apostadores")
 * @UniqueEntity(
 *     fields = {"email"},
 *     message = "La dirección de email ya está registrada."
 * )
 */
class Apostador
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"apuesta"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"apuesta"})
     * @Assert\Email(
     *      message = "Dirección de email inválida"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"apuesta"})
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZáéíóúÁÉÍÓÚüÜ ]+$/",
     *     message = "Ingrese un nombre válido"
     * )
     */
    private $nombre;

    public function __construct(array $data) {
        [
            "email" => $this->email,
            "nombre" => $this->nombre
        ] = $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
