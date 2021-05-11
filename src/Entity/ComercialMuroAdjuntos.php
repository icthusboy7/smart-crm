<?php

namespace App\Entity;

use \DateTime;
use \Exception;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComercialMuroAdjuntos
 *
 * @ORM\Table(name="comercial_muro_adjuntos")
 * @ORM\Entity(repositoryClass="App\Repository\ComercialMuroAdjuntosRepository")
 */
class ComercialMuroAdjuntos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialMuro")
     * @ORM\JoinColumn(name="muro", referencedColumnName="id")
     */
    private $muro;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document")
     * @ORM\JoinColumn(name="doc_id", referencedColumnName="id")
     */

    private $docId;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=12)
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=128)
     */
    private $mimeType;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * ComercialMuroAdjuntos constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->docId;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set doc_id
     *
     * @param Document|null $docId
     *
     * @return ComercialMuroAdjuntos
     */
    public function setDocId(?Document $docId): self
    {
        $this->docId = $docId;

        return $this;
    }

    /**
     * Get doc_id
     *
     * @return Document|null
     */
    public function getDocId(): ?Document
    {
        return $this->docId;
    }

    /**
     * Set ext
     *
     * @param string $ext
     *
     * @return ComercialMuroAdjuntos
     */
    public function setExt(string $ext): self
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get ext
     *
     * @return string
     */
    public function getExt(): string
    {
        return $this->ext;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return ComercialMuroAdjuntos
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Set mime_type
     *
     * @param string $mimeType
     *
     * @return ComercialMuroAdjuntos
     */
    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mime_type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ComercialMuroAdjuntos
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return ComercialMuroAdjuntos
     */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set muro
     *
     * @param ComercialMuro $muro
     *
     * @return ComercialMuroAdjuntos
     */
    public function setMuro(?ComercialMuro $muro = null): self
    {
        $this->muro = $muro;

        return $this;
    }

    /**
     * Get muro
     *
     * @return ComercialMuro|null
     */
    public function getMuro(): ?ComercialMuro
    {
        return $this->muro;
    }
}
