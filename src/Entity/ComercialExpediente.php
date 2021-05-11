<?php
/**
 * Entidad ComercialExpediente
 */

namespace App\Entity;

use \DateTime;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ComercialExpediente
 *
 * @ORM\Table(name="comercial_expediente")
 * @ORM\Entity(repositoryClass="App\Repository\ComercialExpedienteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ComercialExpediente
{
    /**
     * Identificador
     *
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=100, nullable=true)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="cliente_nif", type="string", length=12, nullable=true)
     */
    private $clienteNIF;

    /**
     * @var string
     *
     * @ORM\Column(name="prescriptor_cif", type="string", length=12, nullable=true)
     */
    private $prescriptorCIF;

    /**
     * @var string
     *
     * @ORM\Column(name="oficina", type="string", length=255, nullable=true)
     */
    private $oficina;

    /**
     * @var string
     *
     * @ORM\Column(name="oficina_zona", type="string", length=255, nullable=true)
     */
    private $oficinaZona;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable_caixa", type="string", length=255, nullable=true)
     */
    private $responsableCaixa;

    /**
     * @var float
     *
     * @ORM\Column(name="importe", type="float", nullable=true)
     */
    private $importe;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="fecha_oportunidad", type="datetime", nullable=true)
     */
    private $fechaOportunidad;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha_posible_activacion", type="string", length=7, nullable=true)
     */
    private $fechaPosibleActivacion;

    /**
     * @var int
     *
     * @ORM\Column(name="porcentaje_probabilidad", type="integer", nullable=true)
     */
    private $porcentajeProbabilidad;

    /**
     * @var ComercialExpedienteStatus
     *
     * @ORM\ManyToOne(targetEntity="ComercialExpedienteStatus")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="ComercialExpedientePerdidoMotivo")
     */
    private $statusMotivo;

    /**
     * @var ComercialProducto
     *
     * @ORM\ManyToOne(targetEntity="ComercialProducto")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id", nullable=true)
     */
    private $productoID;

    /**
     * @var TipoCanal
     *
     * @ORM\ManyToOne(targetEntity="TipoCanal")
     * @ORM\JoinColumn(name="canal", referencedColumnName="id", nullable=true)
     */
    private $canal;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=100, nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @var ComercialExpediente
     *
     * @ORM\ManyToOne(targetEntity="ComercialExpediente")
     */
    private $expedientePadre;

    /**
     * @var float
     *
     * @ORM\Column(name="tin", type="float", nullable=true)
     */
    private $tin;

    /**
     * @var bool
     *
     * @ORM\Column(name="eslinea", type="boolean")
     */
    private $eslinea = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="esDisposicion", type="boolean")
     */
    private $esDisposicion = 0;

    /**
     * @var Vertical
     *
     * @ORM\ManyToOne(targetEntity="Vertical")
     * @ORM\JoinColumn(name="vertical", referencedColumnName="id")
     */
    private $vertical;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * Fecha de actualización de expediente
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="deletedBy_id", referencedColumnName="id", nullable=true)
     */
    private $deletedBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="createdBy_id", referencedColumnName="id", nullable=true)
     */
    private $createdBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id", nullable=true)
     */
    private $responsable;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="responsable_gestor_interno_id", referencedColumnName="id", nullable=true)
     */
    private $responsableGestorInterno;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="responsable_gestor_externo_id", referencedColumnName="id", nullable=true)
     */
    private $responsableGestorExterno;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="responsable_riesgos_id", referencedColumnName="id", nullable=true)
     */
    private $responsableRiesgos;

    /**
     * @var bool
     *
     * @ORM\Column(name="no_report", type="boolean")
     */
    private $noReport = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=100, nullable=true)
     */
    private $estado;

    /**
     * @var ComercialAlertas
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialAlertas", mappedBy="expediente")
     */
    private $alertas;

    /**
     * Importe limite
     *
     * @var float
     *
     * @ORM\Column(name="importe_limite", type="float", nullable=true)
     */
    private $importeLimite;

    /**
     * @var float
     *
     * @ORM\Column(name="importe_disponible", type="float", nullable=true)
     */
    private $importeDisponible;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="fecha_vencimiento", type="datetime", nullable=true)
     */
    private $fechaVencimiento;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="fecha_estado", type="datetime", nullable=true)
     */
    private $fechaEstado;

    /**
     * @var string
     *
     * @ORM\Column(name="numLinea", type="string", length=20, nullable=true)
     */
    private $numLinea;

    /**
     * @var int
     *
     * @ORM\Column(name="alerta_disp_id", type="bigint", nullable=true)
     */
    private $alertaDispId;

    /**
     * @var int
     *
     * @ORM\Column(name="alerta_venc_id", type="bigint", nullable=true)
     */
    private $alertaVencId;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialExpedienteCotizaciones", mappedBy="expedienteID")
     */
    private $cotizaciones;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialExpedienteFavoritos", mappedBy="expediente", orphanRemoval=true, cascade={"persist"})
     */
    private $usuariosExpedienteFavorito;

    /**
     * Relación entre Expediente y document
     * @var mixed
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="expedient")
     */
    private $documents;


    /**
     * ComercialExpediente constructor.
     */
    public function __construct()
    {
        $this->createdAt                  = new DateTime('now');
        $this->cotizaciones               = new ArrayCollection();
        $this->esFavorito                 = new ArrayCollection();
        $this->alertas                    = new ArrayCollection();
        $this->usuariosExpedienteFavorito = new ArrayCollection();
        $this->documents                  = new ArrayCollection();
    }

    /**
     * ComercialExpediente toString.
     *
     * @return string|null
     */
    public function __toString(): ?string
    {
        return (string) $this->titulo;
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
     * Set titulo
     *
     * @param string|null $titulo
     *
     * @return ComercialExpediente|null
     */
    public function setTitulo(?string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string|null
     */
    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    /**
     * Set clienteNIF
     *
     * @param string $clienteNIF
     *
     * @return ComercialExpediente|null
     */
    public function setClienteNIF(?string $clienteNIF): self
    {
        $this->clienteNIF = $clienteNIF;

        return $this;
    }

    /**
     * Get clienteNIF
     *
     * @return string|null
     */
    public function getClienteNIF(): ?string
    {
        return $this->clienteNIF;
    }

    /**
     * Set prescriptorCIF
     *
     * @param string $prescriptorCIF
     *
     * @return ComercialExpediente
     */
    public function setPrescriptorCIF(?string $prescriptorCIF): self
    {
        $this->prescriptorCIF = $prescriptorCIF;

        return $this;
    }

    /**
     * Get prescriptorCIF
     *
     * @return string|null
     */
    public function getPrescriptorCIF(): ?string
    {
        return $this->prescriptorCIF;
    }

    /**
     * Set oficina
     *
     * @param string $oficina
     *
     * @return ComercialExpediente
     */
    public function setOficina(?string $oficina): self
    {
        $this->oficina = $oficina;

        return $this;
    }

    /**
     * Get oficina
     *
     * @return string
     */
    public function getOficina(): ?string
    {
        return $this->oficina;
    }

    /**
     * Set importe
     *
     * @param float|null $importe
     *
     * @return ComercialExpediente
     */
    public function setImporte(?float $importe): self
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return float
     */
    public function getImporte(): ?float
    {
        return $this->importe;
    }

    /**
     * Set fechaOportunidad
     *
     * @param DateTime $fechaOportunidad
     *
     * @return ComercialExpediente
     */
    public function setFechaOportunidad(?DateTime $fechaOportunidad): self
    {
        $this->fechaOportunidad = $fechaOportunidad;

        return $this;
    }

    /**
     * Get fechaOportunidad
     *
     * @return string
     */
    public function getFechaOportunidad(): ?string
    {
        return $this->fechaOportunidad;
    }

    /**
     * Set fechaPosibleActivacion
     *
     * @param string $fechaPosibleActivacion
     *
     * @return ComercialExpediente
     */
    public function setFechaPosibleActivacion(?string $fechaPosibleActivacion): self
    {
        $this->fechaPosibleActivacion = $fechaPosibleActivacion;

        return $this;
    }

    /**
     * Get fechaPosibleActivacion
     *
     * @return string
     */
    public function getFechaPosibleActivacion(): ?string
    {
        return $this->fechaPosibleActivacion;
    }

    /**
     * Set porcentajeProbabilidad
     *
     * @param int $porcentajeProbabilidad
     *
     * @return ComercialExpediente
     */
    public function setPorcentajeProbabilidad(?int $porcentajeProbabilidad): self
    {
        $this->porcentajeProbabilidad = $porcentajeProbabilidad;

        return $this;
    }

    /**
     * Get porcentajeProbabilidad
     *
     * @return int
     */
    public function getPorcentajeProbabilidad(): ?int
    {
        return $this->porcentajeProbabilidad;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return ComercialExpediente
     */
    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return ComercialExpediente
     */
    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ComercialExpediente
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
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return ComercialExpediente
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param DateTime $deletedAt
     *
     * @return ComercialExpediente
     */
    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return DateTime
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * Set deletedBy
     *
     * @param User $deletedBy
     *
     * @return ComercialExpediente
     */
    public function setDeletedBy(?User $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * Get deletedBy
     *
     * @return User
     */
    public function getDeletedBy(): ?User
    {
        return $this->deletedBy;
    }

    /**
     * Set productoID
     *
     * @param ?ComercialProducto $productoID
     *
     * @return ComercialExpediente
     */
    public function setProductoID(?ComercialProducto $productoID = null): self
    {
        $this->productoID = $productoID;

        return $this;
    }

    /**
     * Get productoID
     *
     * @return ?ComercialProducto
     */
    public function getProductoID(): ?ComercialProducto
    {
        return $this->productoID;
    }

    /**
     * Set expedientePadre
     *
     * @param ?ComercialExpediente $expedientePadre
     *
     * @return ComercialExpediente
     */
    public function setExpedientePadre(?ComercialExpediente $expedientePadre = null): self
    {
        $this->expedientePadre = $expedientePadre;

        return $this;
    }

    /**
     * Get expedientePadre
     *
     * @return ComercialExpediente
     */
    public function getExpedientePadre(): ?ComercialExpediente
    {
        return $this->expedientePadre;
    }

    /**
     * @return float
     */
    public function getTin(): ?float
    {
        return $this->tin;
    }

    /**
     * @param float|null $tin
     *
     * @return ComercialExpediente
     */
    public function setTin(?float $tin): self
    {
        $this->tin = $tin;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCanal()
    {
        return $this->canal;
    }

    /**
     * @param mixed $canal
     *
     * @return ComercialExpediente
     */
    public function setCanal($canal): self
    {
        $this->canal = $canal;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEslinea(): bool
    {
        return $this->eslinea;
    }

    /**
     * @param bool $eslinea
     *
     * @return ComercialExpediente
     */
    public function setEslinea(bool $eslinea): self
    {
        $this->eslinea = $eslinea;

        return $this;
    }

    /**
     * Get eslinea
     *
     * @return bool
     */
    public function getEslinea(): bool
    {
        return $this->eslinea;
    }

    /**
     * @return bool
     */
    public function isEsDisposicion(): bool
    {
        return $this->esDisposicion;
    }

    /**
     * @param bool $esDisposicion
     *
     * @return ComercialExpediente
     */
    public function setEsDisposicion(bool $esDisposicion): self
    {
        $this->esDisposicion = $esDisposicion;

        return $this;
    }

    /**
     * Get esDisposicion
     *
     * @return bool
     */
    public function getEsDisposicion(): bool
    {
        return $this->esDisposicion;
    }

    /**
     * Set vertical
     *
     * @param ?Vertical $vertical
     *
     * @return ComercialExpediente
     */
    public function setVertical(?Vertical $vertical = null): self
    {
        $this->vertical = $vertical;

        return $this;
    }

    /**
     * Get vertical
     *
     * @return Vertical
     */
    public function getVertical(): ?Vertical
    {
        return $this->vertical;
    }

    /**
     * @return int
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     *
     * @return ComercialExpediente
     */
    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return User|null
     */
    public function getResponsable(): ?User
    {
        return $this->responsable;
    }

    /**
     * @param User|null $responsable
     *
     * @return ComercialExpediente
     */
    public function setResponsable(?User $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @throws \Exception
     */
    public function preUpdate(): void
    {
        if (is_null($this->createdAt)) {
            $this->setCreatedAt(new DateTime('now'));
        }

        $this->setUpdatedAt(new DateTime('now'));
    }

    /**
     * Add cotizaciones
     *
     * @param ComercialExpedienteCotizaciones $cotizaciones
     *
     * @return ComercialExpediente
     */
    public function addCotizacione(ComercialExpedienteCotizaciones $cotizaciones): self
    {
        $this->cotizaciones[] = $cotizaciones;

        return $this;
    }

    /**
     * Remove cotizaciones
     *
     * @param ComercialExpedienteCotizaciones $cotizaciones
     */
    public function removeCotizacione(ComercialExpedienteCotizaciones $cotizaciones): void
    {
        $this->cotizaciones->removeElement($cotizaciones);
    }

    /**
     * Get cotizaciones
     *
     * @return Collection
     */
    public function getCotizaciones(): ?Collection
    {
        return $this->cotizaciones;
    }

    /**
     * Set status
     *
     * @param ?ComercialExpedienteStatus $status
     *
     * @return ComercialExpediente
     */
    public function setStatus(?ComercialExpedienteStatus $status = null): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return ComercialExpedienteStatus|null
     */
    public function getStatus(): ?ComercialExpedienteStatus
    {
        return $this->status;
    }

    /**
     * Set statusMotivo
     *
     * @param ComercialExpedientePerdidoMotivo|null $statusMotivo
     *
     * @return ComercialExpediente
     */
    public function setStatusMotivo(?ComercialExpedientePerdidoMotivo $statusMotivo = null): self
    {
        $this->statusMotivo = $statusMotivo;

        return $this;
    }

    /**
     * Get statusMotivo
     *
     * @return ComercialExpedientePerdidoMotivo
     */
    public function getStatusMotivo(): ?int
    {
        return $this->statusMotivo;
    }

    /**
     * Set responsable_gestor_interno
     *
     * @param User|null $responsableGestorInterno
     *
     * @return ComercialExpediente
     */
    public function setResponsableGestorInterno(?User $responsableGestorInterno = null): self
    {
        $this->responsableGestorInterno = $responsableGestorInterno;

        return $this;
    }

    /**
     * Get responsable_gestor_interno
     *
     * @return User|null
     */
    public function getResponsableGestorInterno(): ?User
    {
        return $this->responsableGestorInterno;
    }

    /**
     * Set responsable_gestor_externo
     *
     * @param User|null $responsableGestorExterno
     *
     * @return ComercialExpediente
     */
    public function setResponsableGestorExterno(?User $responsableGestorExterno = null): self
    {
        $this->responsableGestorExterno = $responsableGestorExterno;

        return $this;
    }

    /**
     * Get responsable_gestor_externo
     *
     * @return User|null
     */
    public function getResponsableGestorExterno(): ?User
    {
        return $this->responsableGestorExterno;
    }

    /**
     * Set responsable_riesgos
     *
     * @param User|null $responsableRiesgos
     *
     * @return self
     */
    public function setResponsableRiesgos(?User $responsableRiesgos = null): self
    {
        $this->responsableRiesgos = $responsableRiesgos;

        return $this;
    }

    /**
     * Get responsable_riesgos
     *
     * @return User|null
     */
    public function getResponsableRiesgos(): ?User
    {
        return $this->responsableRiesgos;
    }

    /**
     * @return string
     */
    public function getResponsableCaixa(): ?string
    {
        return $this->responsableCaixa;
    }

    /**
     * @param string $responsableCaixa
     *
     * @return ComercialExpediente
     */
    public function setResponsableCaixa(?string $responsableCaixa): self
    {
        if ('-' !== $responsableCaixa) {
            $this->responsableCaixa = $responsableCaixa;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getOficinaZona(): ?string
    {
        return $this->oficinaZona;
    }

    /**
     * @param string $oficinaZona
     *
     * @return ComercialExpediente
     */
    public function setOficinaZona(?string $oficinaZona): self
    {
        $this->oficinaZona = $oficinaZona;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNoReport(): bool
    {
        return $this->noReport;
    }

    /**
     * @return bool
     */
    public function getNoReport(): bool
    {
        return $this->noReport;
    }



    /**
     * @param bool $noReport
     *
     * @return ComercialExpediente
     */
    public function setNoReport(bool $noReport): self
    {
        $this->noReport = $noReport;

        return $this;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return ComercialExpediente
     */
    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado(): ?string
    {
        return $this->estado;
    }

    /**
     * Set importe_limite
     *
     * @param float $importeLimite
     *
     * @return ComercialExpediente
     */
    public function setImporteLimite(?float $importeLimite): self
    {
        $this->importeLimite = $importeLimite;

        return $this;
    }

    /**
     * Get importe_limite
     *
     * @return float|null
     */
    public function getImporteLimite(): ?float
    {
        return $this->importeLimite;
    }

    /**
     * Set importe_disponible
     *
     * @param float|null $importeDisponible
     *
     * @return ComercialExpediente
     */
    public function setImporteDisponible(?float $importeDisponible): self
    {
        $this->importeDisponible = $importeDisponible;

        return $this;
    }

    /**
     * Get importe_disponible
     *
     * @return float|null
     */
    public function getImporteDisponible(): ?float
    {
        return $this->importeDisponible;
    }

    /**
     * Set fecha_vencimiento
     *
     * @param DateTime $fechaVencimiento
     *
     * @return ComercialExpediente
     */
    public function setFechaVencimiento(?DateTime $fechaVencimiento): self
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fecha_vencimiento
     *
     * @return DateTime|null
     */
    public function getFechaVencimiento(): ?DateTime
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set fecha_estado
     *
     * @param DateTime $fechaEstado
     *
     * @return ComercialExpediente
     */
    public function setFechaEstado(?DateTime $fechaEstado): self
    {
        $this->fechaEstado = $fechaEstado;

        return $this;
    }

    /**
     * Get fecha_estado
     *
     * @return DateTime|null
     */
    public function getFechaEstado(): ?DateTime
    {
        return $this->fechaEstado;
    }

    /**
     * Set numLinea
     *
     * @param string $numLinea
     *
     * @return ComercialExpediente
     */
    public function setNumLinea(string $numLinea): self
    {
        $this->numLinea = $numLinea;

        return $this;
    }

    /**
     * Get numLinea
     *
     * @return string|null
     */
    public function getNumLinea(): ?string
    {
        return $this->numLinea;
    }

    /**
     * Set alerta_disp_id
     *
     * @param int $alertaDispId
     *
     * @return ComercialExpediente
     */
    public function setAlertaDispId(int $alertaDispId): self
    {
        $this->alertaDispId = $alertaDispId;

        return $this;
    }

    /**
     * Get alerta_disp_id
     *
     * @return int|null
     */
    public function getAlertaDispId(): ?int
    {
        return $this->alertaDispId;
    }

    /**
     * Set alerta_venc_id
     *
     * @param int $alertaVencId
     *
     * @return ComercialExpediente
     */
    public function setAlertaVencId(int $alertaVencId): self
    {
        $this->alertaVencId = $alertaVencId;

        return $this;
    }

    /**
     * Get alerta_venc_id
     *
     * @return int
     */
    public function getAlertaVencId(): ?int
    {
        return $this->alertaVencId;
    }

    /**
     * @return Collection|ComercialAlertas[]
     */
    public function getAlertas(): Collection
    {
        return $this->alertas;
    }

    /**
     * Añadir alerta al expediente
     *
     * @param ComercialAlertas $alerta
     *
     * @return ComercialExpediente
     */
    public function addAlerta(ComercialAlertas $alerta): self
    {
        if (!$this->alertas->contains($alerta)) {
            $this->alertas[] = $alerta;
            $alerta->setExpediente($this);
        }

        return $this;
    }

    /**
     * Eliminar alerta del expediente
     *
     * @param ComercialAlertas $alerta
     *
     * @return ComercialExpediente
     */
    public function removeAlerta(ComercialAlertas $alerta): self
    {
        if ($this->alertas->contains($alerta)) {
            $this->alertas->removeElement($alerta);
            // set the owning side to null (unless already changed)
            if ($alerta->getExpediente() === $this) {
                $alerta->setExpediente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ComercialExpedienteFavoritos[]
     */
    public function getUsuariosExpedienteFavorito(): Collection
    {
        return $this->usuariosExpedienteFavorito;
    }

    /**
     * @param ComercialExpedienteFavoritos $usuariosExpedienteFavorito
     *
     * @return ComercialExpediente
     */
    public function addUsuariosExpedienteFavorito(ComercialExpedienteFavoritos $usuariosExpedienteFavorito): self
    {
        if (!$this->usuariosExpedienteFavorito->contains($usuariosExpedienteFavorito)) {
            $this->usuariosExpedienteFavorito[] = $usuariosExpedienteFavorito;
            $usuariosExpedienteFavorito->setExpediente($this);
        }

        return $this;
    }

    /**
     * @param ComercialExpedienteFavoritos $usuariosExpedienteFavorito
     *
     * @return ComercialExpediente
     */
    public function removeUsuariosExpedienteFavorito(ComercialExpedienteFavoritos $usuariosExpedienteFavorito): self
    {
        if ($this->usuariosExpedienteFavorito->contains($usuariosExpedienteFavorito)) {
            $this->usuariosExpedienteFavorito->removeElement($usuariosExpedienteFavorito);
            // set the owning side to null (unless already changed)
            if ($usuariosExpedienteFavorito->getExpediente() === $this) {
                $usuariosExpedienteFavorito->setExpediente(null);
            }
        }
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    /**
     * agrega el expediente al document
     * @param Document $document
     *
     * @return ComercialExpediente
     */
    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setExpedient($this);
        }

        return $this;
    }
}
