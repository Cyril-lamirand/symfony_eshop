<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart_product;

    /**
     * @ORM\Column(type="integer")
     */
    private $cart_quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cart_datecreate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cart_state;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCartProduct(): ?Product
    {
        return $this->cart_product;
    }

    public function setCartProduct(?Product $cart_product): self
    {
        $this->cart_product = $cart_product;

        return $this;
    }

    public function getCartQuantity(): ?int
    {
        return $this->cart_quantity;
    }

    public function setCartQuantity(int $cart_quantity): self
    {
        $this->cart_quantity = $cart_quantity;

        return $this;
    }

    public function getCartDatecreate(): ?\DateTimeInterface
    {
        return $this->cart_datecreate;
    }

    public function setCartDatecreate(\DateTimeInterface $cart_datecreate): self
    {
        $this->cart_datecreate = $cart_datecreate;

        return $this;
    }

    public function getCartState(): ?bool
    {
        return $this->cart_state;
    }

    public function setCartState(bool $cart_state): self
    {
        $this->cart_state = $cart_state;

        return $this;
    }
}
