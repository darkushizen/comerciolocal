<?php

namespace App\Entity;

use App\Repository\CanjeoRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=CanjeoRepository::class)
 */
class Canjeo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $valor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cabecera;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(int $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getCabecera(): ?string
    {
        return $this->cabecera;
    }

    public function setCabecera(string $cabecera): self
    {
        $this->cabecera = $cabecera;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha2(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function setFecha(): self
    {
        $fecha1 = new DateTime('now');
        #$fecha1 = DateTime::getDateTime();
        $this->fecha = $fecha1;

        return $this;
    }

    public function setUsuarioId($user): self
    {
        #$user = $this->security->getUser();
        #$id = $user->getId();
        #$this->usuario_id_id = $id;
        #$user = $this->get('security.token_storage')->getToken()->getUser();
        #$id = $user->getId();
        //$id = $this->getUser();
        $this->usuario_id = $user;
        return $this;
    }
}
