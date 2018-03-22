<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class OrderProductRepository extends EntityRepository
{

    /**
     * @param $orderProductId
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdJoinToOrder($orderProductId){

        return $this->createQueryBuilder('op')

            ->innerJoin('op.order','o')
            ->addSelect('p')
            ->andWhere('op.id = :id')
            ->setParameter('id' , $orderProductId)
            ->getQuery()
            ->getOneOrNullResult();

    }

    /**
     * @param $orderId
     * @return array
     *
     */
    public function findProductsByOrderId($order){

        return $this->createQueryBuilder('op')
            ->innerJoin('op.product', 'p')
            ->addSelect('p')
            ->andWhere('op.order = :id')
            ->setParameter('id', $order)
            ->getQuery()
            ->getArrayResult();

    }

}