<?php

namespace App\Entity;

use App\Repository\PremioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Security;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=PremioRepository::class)
 */
class Premio
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
    private $cabecera;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer")
     */
    private $valor;

    /**
     * @ORM\Column(type="integer")
     */
    private $ndisponibles;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(int $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getNdisponibles(): ?int
    {
        return $this->ndisponibles;
    }

    public function setNdisponibles(int $ndisponibles): self
    {
        $this->ndisponibles = $ndisponibles;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    #public function setFecha(\DateTimeInterface $fecha): self
    public function setFecha(): self
    {
        $fecha1 = new DateTime('now');
        #$fecha1 = DateTime::getDateTime();
        $this->fecha = $fecha1;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }


    //---------------------------------------pruebas

    /**
     * @var Security
     */
    private $security;

    /*public function __construct(Security $security)
    {
        $this->security = $security;
    }*/


    #public function setUsuarioId(?string $id): self
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
