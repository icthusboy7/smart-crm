<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TipoCanalRepository")
 */
class TipoCanal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $canal_desc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCanalDesc(): ?string
    {
        return $this->canal_desc;
    }

    public function setCanalDesc(string $canal_desc): self
    {
        $this->canal_desc = $canal_desc;

        return $this;
    }
}
