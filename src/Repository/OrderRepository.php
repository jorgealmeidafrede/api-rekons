<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    /**
     * @param $date
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUserAndDateJoinOrderProduct($user ,$date){

//        $user = $this->getUser();

//        print_r($user->getId());die();

        return $this->createQueryBuilder('o')

            ->innerJoin('o.orderProduct','op')
            ->addSelect('op')
            ->andWhere('o.date = :date')
            ->andWhere('o.user = :user')
            ->setParameter('date' , $date)
            ->setParameter('user' , $user)
            ->getQuery()
            ->getArrayResult();
    }

    public function findByToday(){

        $date = date('Y-m-d');

//        print_r($date);die();

        return $this->createQueryBuilder('o')
            ->innerJoin('o.orderProduct','op')
            ->andWhere('o.date = :date')
            ->setParameter('date' , $date)
            ->getQuery()
            ->getResult();
    }


//    public function findByToday(){
//
//        $date = date('Y-m-d');
//
////        print_r($date);die();
//
//        return $this->createQueryBuilder('o')
//            ->innerJoin('o.orderProduct','op')
//            ->andWhere('o.date = :date')
//            ->setParameter('date' , $date)
//            ->getQuery()
//            ->getResult();
//    }

}