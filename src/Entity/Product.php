<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Table(name="products");
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=150, unique=true)
     */
    protected $name;

    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @ORM\Column(name="status", type="boolean", options={"default": 1})
     */
    protected $status;
    /**
     * @ORM\Column(name="code", type="string", length=10, unique=true)
     */
    protected $code;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="product")
     */
    protected $orderProduct;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->orderProduct = new ArrayCollection();
        $this->status = true;
    }

    /**
     * @return mixed
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStatus ()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus ($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCode ()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode ($code)
    {
        $this->code = $code;
    }


    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $dateTimeNow = new DateTime('now');
        $this->setUpdatedAt($dateTimeNow);
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    /**
     * @return mixed
     */
    public function getOrderProduct ()
    {
        return $this->orderProduct;
    }

    /**
     * @param mixed $orderProduct
     */
    public function setOrderProduct ($orderProduct)
    {
        $this->orderProduct = $orderProduct;
    }
}
