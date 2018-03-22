<?php
/**
 * Order.php
 *
 * Order Entity
 *
 * @category   Entity
 * @package    MyKanban
 * @author     Francisco Ugalde
 * @copyright  2018 www.franciscougalde.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use JMS\Serializer\Annotation as Serializer;

/**
 * Order
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="status", type="boolean", options={"default": 1})
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="order")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Exclude()
     */
    protected $user;

    /**
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="order")
     */
    protected $orderProduct;

    public function __construct()
    {
        $this->orderProduct = new ArrayCollection();
        $this->date =  new DateTime('now');
        $this->status = true;
    }

    /**
     * @return mixed
     */
    public function getOrderProduct()
    {
        return $this->orderProduct;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
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
     * @return self
     */
    public function setStatus ($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getDate ()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return self
     */
    public function setDate ($date)
    {
        $this->date = $date;
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
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
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


}
