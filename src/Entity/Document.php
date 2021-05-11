<?php
/**
 * Entidad Document
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Document
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
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
     * DocumentType
     * @var mixed
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentType", inversedBy="documents")
     */
    private $documentType;

    /**
     * Nombre documento
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * ID Dokuflex
     * @var string
     *
     * @ORM\Column(name="idDoku", type="string", length=255, nullable=true)
     */
    private $idDoku;

    /**
     * Relación entre expediente y document
     * @var mixed
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialExpediente", inversedBy="documents")
     */
    private $expedient;

    /**
     * Relación entre cotizacion y document
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quotation;

    /**
     * Relación entre oficina y document
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $office;

    /**
     * Relación entre cliente y document
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customer;

    /**
     * Relación entre proveedor y document
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $provider;

    /**
     * Ruta documento
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * Observaciones documento
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;

    /**
     * Relación entre User y document
     * @var mixed
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="documents")
     */
    private $user;

    /**
     * Fecha de creación
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * Obtener identificador
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * establecer tipo documento
     * @return mixed
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * setear tipo documento
     * @param mixed $documentType
     *
     * @return Document
     */
    public function setDocumentType($documentType): self
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Obtener nombre
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Establecer nombre
     * @param mixed $name
     *
     * @return Document
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Obtener ID dokuflex
     * @return string|null
     */
    public function getIdDoku(): ?string
    {
        return $this->idDoku;
    }

    /**
     * Establecer ID dokuflex
     * @param string $idDoku
     *
     * @return Document
     */
    public function setIdDoku(string $idDoku): self
    {
        $this->idDoku = $idDoku;

        return $this;
    }

    /**
     * obtener expediente
     * @return mixed
     */
    public function getExpedient()
    {
        return $this->expedient;
    }

    /**
     * setear expediente
     * @param mixed $expedient
     *
     * @return Document
     */
    public function setExpedient($expedient): self
    {
        $this->expedient = $expedient;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuotation(): ?string
    {
        return $this->quotation;
    }

    /**
     * @param string|null $quotation
     *
     * @return Document
     */
    public function setQuotation(?string $quotation): self
    {
        $this->quotation = $quotation;

        return $this;
    }

    /**
     * obtener oficina
     * @return string
     */
    public function getOffice(): string
    {
        return $this->office;
    }

    /**
     * setear oficina
     * @param string $office
     *
     * @return Document
     */
    public function setOffice(?string $office): self
    {
        $this->office = $office;

        return $this;
    }

    /**
     * obtener cliente
     * @return string
     */
    public function getCustomer(): string
    {
        return $this->customer;
    }

    /**
     * setear cliente
     * @param string $customer
     *
     * @return Document
     */
    public function setCustomer(?string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * obtener proveedor
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * setear proveedor
     * @param string $provider
     *
     * @return Document
     */
    public function setProvider(?string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Obtener path
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Establecer path
     * @param mixed $path
     *
     * @return Document
     */
    public function setPath($path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Obtener Observaciones
     * @return mixed
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Establecer observaciones
     * @param mixed $observations
     *
     * @return Document
     */
    public function setObservations($observations): self
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * obtener usuario
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * setear usuario
     * @param mixed $user
     *
     * @return Document
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * obtiene createdAt
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Establece createdAt
     *
     * @param \DateTimeInterface|null $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param array $data
     *
     * @throws \Exception
     */
    public function setData(array $data): void
    {

        $this->setDocumentType($data['TIPO']);

        if (isset($data['EXPEDIENTE'])) {
            $this->setExpedient($data['EXPEDIENTE']);
        }
        if (isset($data['COTIZACION'])) {
            $this->setQuotation($data['COTIZACION']);
        }
        if (isset($data['OFICINA'])) {
            $this->setOffice($data['OFICINA']);
        }
        if (isset($data['CLIENTE'])) {
            $this->setCustomer($data['CLIENTE']);
        }
        if (isset($data['PROVEEDOR'])) {
            $this->setProvider($data['PROVEEDOR']);
        }

        $this->setName($data['NOMBRE']);

        $this->setIdDoku($data['IDDOKU']);
        //$this->setPath($md5.'.'.$file->getClientOriginalExtension());
        if (isset($data['OBSERVACIONES'])) {
            $this->setObservations($data['OBSERVACIONES']);
        }
        $this->setUser($data['USER']);
        $this->setCreatedAt(new \DateTime('now'));
    }
}
