<?php
/**
 * Entidad OficinaContactos
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clase OficinaContactos
 * @ORM\Entity(repositoryClass="App\Repository\OficinaContactosRepository")
 */
class OficinaContactos
{
    /**
     * identificador de OficinaContactos
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Codigo de la oficina
     * @ORM\Column(type="string", length=255)
     */
    private $CodigoOficina;

    /**
     * nif del contacto
     * @ORM\Column(type="string", length=255)
     */
    private $nifContacto;

    /**
     * obtiene el identificador de OficinasContactos
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el CodigoOficina de OficinasContactos
     * @return string|null
     */
    public function getCodigoOficina(): ?string
    {
        return $this->CodigoOficina;
    }

    /**
     * establece el CodigoOficina de OficinasContactos
     * @param string $CodigoOficina
     *
     * @return OficinaContactos
     */
    public function setCodigoOficina(string $CodigoOficina): self
    {
        $this->CodigoOficina = $CodigoOficina;

        return $this;
    }

    /**
     * obtiene el nif de OficinasContactos
     * @return null|string
     */
    public function getNifContacto(): ?string
    {
        return $this->nifContacto;
    }

    /**
     * Establece el nif de OficinasContactos
     * @param string $nifContacto
     * @return OficinaContactos
     */
    public function setNifContacto(string $nifContacto): self
    {
        $this->nifContacto = $nifContacto;

        return $this;
    }
}
