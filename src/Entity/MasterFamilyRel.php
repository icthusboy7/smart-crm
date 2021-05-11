<?php
/**
 * Entidad MasterFamilyRel
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterFamilyRelRepository")
 * @ORM\Table(name="maestra_familia_rel")
 */
class MasterFamilyRel
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $derf;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idFamilia;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idProducto;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $manDT;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDerf(): ?string
    {
        return $this->derf;
    }

    /**
     * @param string|null $derf
     *
     * @return MasterFamilyRel
     */
    public function setDerf(?string $derf): self
    {
        $this->derf = $derf;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdFamilia(): ?string
    {
        return $this->idFamilia;
    }

    /**
     * @param string|null $idFamilia
     *
     * @return MasterFamilyRel
     */
    public function setIdFamilia(?string $idFamilia): self
    {
        $this->idFamilia = $idFamilia;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdProducto(): ?string
    {
        return $this->idProducto;
    }

    /**
     * @param string|null $idProducto
     *
     * @return MasterFamilyRel
     */
    public function setIdProducto(?string $idProducto): self
    {
        $this->idProducto = $idProducto;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getManDT(): ?string
    {
        return $this->manDT;
    }

    /**
     * @param string|null $manDT
     *
     * @return MasterFamilyRel
     */
    public function setManDT(?string $manDT): self
    {
        $this->manDT = $manDT;

        return $this;
    }

    /**
     * obtiene createdAt
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Establece createdAt
     *
     * @param DateTimeInterface|null $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param array $row
     * @param int   $columnIni
     *
     * @throws Exception
     */
    public function setData(array $row, int $columnIni): void
    {
        $this->setDerf($row[1]);
        $this->setIdFamilia((string) $row[$columnIni]);
        $this->setIdProducto((string) $row[3]);
        $this->setManDT((string) $row[4]);
        $this->setCreatedAt(new DateTime('now'));
    }
}
