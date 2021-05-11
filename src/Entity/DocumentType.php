<?php
/**
 * Entidad DocumentType
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Document type
 * @ORM\Entity(repositoryClass="App\Repository\DocumentTypeRepository")
 */
class DocumentType
{
    /**
     * Identificador
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Codigo
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * Descripción
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $description;

    /**
     * Relación entre documentType y document
     * @var mixed
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="documentType")
     */
    private $documents;

    /**
     * ComercialExpediente constructor.
     */
    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    /**
     * Obtener identificador
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Establecer identificador
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Obtener codigo
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Establecer codigo
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * Obtener descripcion
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Establecer descripcion
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    /**
     * agrega el tipo de documento al document
     * @param Document $document
     *
     * @return DocumentType
     */
    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setDocumentType($this);
        }

        return $this;
    }
}
