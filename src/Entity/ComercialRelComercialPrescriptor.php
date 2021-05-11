<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="comercial_rel_comercial_prescriptor")
 * @ORM\Entity(repositoryClass="App\Repository\ComercialRelComercialPrescriptorRepository")
 */
class ComercialRelComercialPrescriptor
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
     * @ORM\Column(name="comercial", type="string", length=255)
     */
    private $comercial;

    /**
     * @var string
     *
     * @ORM\Column(name="prescriptor", type="string", length=255)
     */
    private $prescriptor;

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
    public function getComercial(): string
    {
        return $this->comercial;
    }

    /**
     * @param string $comercial
     */
    public function setComercial(string $comercial): void
    {
        $this->comercial = $comercial;
    }

    /**
     * @return string
     */
    public function getPrescriptor(): string
    {
        return $this->prescriptor;
    }

    /**
     * @param string $prescriptor
     */
    public function setPrescriptor(string $prescriptor): void
    {
        $this->prescriptor = $prescriptor;
    }
}
