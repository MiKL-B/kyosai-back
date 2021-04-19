<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CartRepository;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
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
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="produits")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=Produits::class, inversedBy="users")
     * @JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit_id;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of produit_id
     */ 
    public function getProduit_id()
    {
        return $this->produit_id;
    }

    /**
     * Set the value of produit_id
     *
     * @return  self
     */ 
    public function setProduit_id($produit_id)
    {
        $this->produit_id = $produit_id;

        return $this;
    }
}
