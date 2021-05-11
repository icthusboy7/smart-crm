<?php
/**
 * Entidad MasterFamily
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterFamilyRepository")
 * @ORM\Table(name="maestra_familia")
 */
class MasterFamily
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
    private $descripcion;

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
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string|null $descripcion
     *
     * @return MasterFamily
     */
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

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
     * @return MasterFamily
     */
    public function setIdFamilia(?string $idFamilia): self
    {
        $this->idFamilia = $idFamilia;

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
     * @return MasterFamily
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
        $this->setDescripcion($row[0]);
        $this->setIdFamilia((string) $row[$columnIni]);
        $this->setManDT((string) $row[2]);
        $this->setCreatedAt(new DateTime('now'));
    }
}
