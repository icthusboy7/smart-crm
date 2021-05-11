<?php
/**
 * Entidad MasterEmployee
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterEmployeeRepository")
 * @ORM\Table(name="maestra_empleados_cxb")
 */
class MasterEmployee
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido1", type="string", length=255, nullable=true)
     */
    private $primerApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido2", type="string", length=255, nullable=true)
     */
    private $segundoApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="cargo", type="string", length=255, nullable=true)
     */
    private $cargo;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoOficina", type="string", length=255, nullable=true)
     */
    private $codigoOficina;

    /**
     * @var string
     *
     * @ORM\Column(name="idCargo", type="string", length=255, nullable=true)
     */
    private $idCargo;

    /**
     * @var string
     *
     * @ORM\Column(name="idResp", type="string", length=255, nullable=true)
     */
    private $idResp;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreGestor", type="string", length=255, nullable=true)
     */
    private $nombreGestor;

    /**
     * @var string
     *
     * @ORM\Column(name="numEmpleado", type="string", length=255, nullable=true)
     */
    private $numEmpleado;

    /**
     * @var string
     *
     * @ORM\Column(name="resp", type="string", length=255, nullable=true)
     */
    private $resp;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoCartera", type="string", length=255, nullable=true)
     */
    private $tipoCartera;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoGestor", type="string", length=255, nullable=true)
     */
    private $tipoGestor;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPrimerApellido(): string
    {
        return $this->primerApellido;
    }

    /**
     * @param string $primerApellido
     */
    public function setPrimerApellido(string $primerApellido): void
    {
        $this->primerApellido = $primerApellido;
    }

    /**
     * @return string
     */
    public function getSegundoApellido(): string
    {
        return $this->segundoApellido;
    }

    /**
     * @param string $segundoApellido
     */
    public function setSegundoApellido(string $segundoApellido): void
    {
        $this->segundoApellido = $segundoApellido;
    }

    /**
     * @return string
     */
    public function getCargo(): string
    {
        return $this->cargo;
    }

    /**
     * @param string $cargo
     */
    public function setCargo(string $cargo): void
    {
        $this->cargo = $cargo;
    }

    /**
     * @return string
     */
    public function getCodigoOficina(): string
    {
        return $this->codigoOficina;
    }

    /**
     * @param string $codigoOficina
     */
    public function setCodigoOficina(string $codigoOficina): void
    {
        $this->codigoOficina = $codigoOficina;
    }

    /**
     * @return string
     */
    public function getIdCargo(): string
    {
        return $this->idCargo;
    }

    /**
     * @param string $idCargo
     */
    public function setIdCargo(string $idCargo): void
    {
        $this->idCargo = $idCargo;
    }

    /**
     * @return string
     */
    public function getIdResp(): string
    {
        return $this->idResp;
    }

    /**
     * @param string $idResp
     */
    public function setIdResp(string $idResp): void
    {
        $this->idResp = $idResp;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getNombreGestor(): string
    {
        return $this->nombreGestor;
    }

    /**
     * @param string $nombreGestor
     */
    public function setNombreGestor(string $nombreGestor): void
    {
        $this->nombreGestor = $nombreGestor;
    }

    /**
     * @return string
     */
    public function getNumEmpleado(): string
    {
        return $this->numEmpleado;
    }

    /**
     * @param string $numEmpleado
     */
    public function setNumEmpleado(string $numEmpleado): void
    {
        $this->numEmpleado = $numEmpleado;
    }

    /**
     * @return string
     */
    public function getResp(): string
    {
        return $this->resp;
    }

    /**
     * @param string $resp
     */
    public function setResp(string $resp): void
    {
        $this->resp = $resp;
    }

    /**
     * @return string
     */
    public function getTipoCartera(): string
    {
        return $this->tipoCartera;
    }

    /**
     * @param string $tipoCartera
     */
    public function setTipoCartera(string $tipoCartera): void
    {
        $this->tipoCartera = $tipoCartera;
    }

    /**
     * @return string
     */
    public function getTipoGestor(): string
    {
        return $this->tipoGestor;
    }

    /**
     * @param string $tipoGestor
     */
    public function setTipoGestor(string $tipoGestor): void
    {
        $this->tipoGestor = $tipoGestor;
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
        $this->setPrimerApellido($row[0]);
        $this->setSegundoApellido($row[1]);
        $this->setCargo($row[2]);
        $this->setCodigoOficina((string) $row[3]);
        $this->setIdCargo($row[4]);
        $this->setIdResp($row[5]);
        $this->setMail($row[6]);
        $this->setNombre($row[7]);
        $this->setNombreGestor($row[8]);
        $this->setNumEmpleado($row[$columnIni]);
        $this->setResp($row[10]);
        $this->setTipoCartera($row[11]);
        $this->setTipoGestor($row[12]);
        $this->setCreatedAt(new DateTime('now'));
    }
}
