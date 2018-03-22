<?php
/**
 * Created by PhpStorm.
 * User: jorge
 * Date: 9/03/18
 * Time: 22:29
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * OrderProduct
 *
 * @ORM\Table(name="orders_products");
 * @ORM\Entity(repositoryClass="App\Repository\OrderProductRepository");
 */

class OrderProduct
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderProduct")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="orderProduct")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @Serializer\Exclude()
     */
    protected $product;

    /** @ORM\Column(type="integer", name="quantity") */
    protected $quantity;

    /*
     * Order
     */

    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /*
     * Product
     */

    public function getProduct(): Order
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    /*
     * Quantity
     */

    /**
     * @return mixed
     */
    public function getQuantity ()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity ($quantity)
    {
        $this->quantity = $quantity;
    }

}