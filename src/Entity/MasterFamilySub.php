<?php
/**
 * Entidad MasterFamilySub
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterFamilySubRepository")
 * @ORM\Table(name="maestra_subfamilia")
 */
class MasterFamilySub
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
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idSubfamilia;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idTipoMaquina;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tipoVehiculoInd;

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
     * @return MasterFamilySub
     */
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGrupo(): ?string
    {
        return $this->grupo;
    }

    /**
     * @param string|null $grupo
     *
     * @return MasterFamilySub
     */
    public function setGrupo(?string $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdSubfamilia(): ?string
    {
        return $this->idSubfamilia;
    }

    /**
     * @param string|null $idSubfamilia
     *
     * @return MasterFamilySub
     */
    public function setIdSubfamilia(?string $idSubfamilia): self
    {
        $this->idSubfamilia = $idSubfamilia;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdTipoMaquina(): ?string
    {
        return $this->idTipoMaquina;
    }

    /**
     * @param string|null $idTipoMaquina
     *
     * @return MasterFamilySub
     */
    public function setIdTipoMaquina(?string $idTipoMaquina): self
    {
        $this->idTipoMaquina = $idTipoMaquina;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTipoVehiculoInd(): ?string
    {
        return $this->tipoVehiculoInd;
    }

    /**
     * @param string|null $tipoVehiculoInd
     *
     * @return MasterFamilySub
     */
    public function setTipoVehiculoInd(?string $tipoVehiculoInd): self
    {
        $this->tipoVehiculoInd = $tipoVehiculoInd;

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
        $this->setDescripcion($row[20]);
        $this->setGrupo((string) $row[22]);
        $this->setIdSubfamilia((string) $row[$columnIni]);
        $this->setIdTipoMaquina((string) $row[25]);
        $this->setTipoVehiculoInd((string) $row[37]);
        $this->setCreatedAt(new DateTime('now'));
    }
}
