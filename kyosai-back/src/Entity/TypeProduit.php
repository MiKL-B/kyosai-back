<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TypeProduitRepository::class)
 */
class TypeProduit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $badge;

    /**
     * @ORM\Column(type="integer")
     */
    private $dessin;

    /**
     * @ORM\Column(type="integer")
     */
    private $papercraft;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBadge(): ?int
    {
        return $this->badge;
    }

    public function setBadge(int $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getDessin(): ?int
    {
        return $this->dessin;
    }

    public function setDessin(int $dessin): self
    {
        $this->dessin = $dessin;

        return $this;
    }

    public function getPapercraft(): ?int
    {
        return $this->papercraft;
    }

    public function setPapercraft(int $papercraft): self
    {
        $this->papercraft = $papercraft;

        return $this;
    }
}
