<?php

namespace App\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\User;

/**
 * Class PruebasController
 *
 * @Route("/")
 */
//* @Route("/api")
class PruebasController extends FOSRestController
{

    /**
     * @Rest\Get("/api/v1/getorder", name="getorder")
     */

    public function getOrderProductByUser (Request $request)
    {
        $date = new \DateTime('20-04-2018');
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
//        var_dump($user->getId());die();

//
// recupero order segun fecha y usuario
//       $order = $em->getRepository('App:Order')->findByUserAndDateJoinOrderProduct($user, $date);

        $order = $em->getRepository('App:Order')->findByToday();


        return new Response($serializer->serialize($order , "json"));

    }

    /**
     * @Rest\Get("/orderid/{id}.{_format}", name="orderById" , defaults={"_format":"json"})
     */
    public function orderById ($id)
    {

        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('App:Order')->find($id);
        $products = $em->getRepository('App:OrderProduct')->findProductsByOrderId($order);

        return new Response($serializer->serialize($products , "json"));
    }


    /**
     * @Rest\Get("/json", name="json")
     */
    public function jsonReview ()
    {

//        $json = "[{\"id\":3,\"status\":true,\"date\":\"2018-04-20T00:00:00+00:00\",\"createdAt\":\"2018-03-21T00:24:32+00:00\",\"updatedAt\":\"2018-03-21T00:24:32+00:00\",\"orderProduct\":[{\"id\":5,\"quantity\":12},{\"id\":6,\"quantity\":16}]},{\"id\":4,\"status\":true,\"date\":\"2018-04-20T00:00:00+00:00\",\"createdAt\":\"2018-03-21T00:26:36+00:00\",\"updatedAt\":\"2018-03-21T00:26:36+00:00\",\"orderProduct\":[{\"id\":7,\"quantity\":12},{\"id\":8,\"quantity\":16}]}]";

//        $json = "[{\"id\":2,\"status\":true,\"date\":\"2018-03-21T00:00:00+00:00\",\"created_at\":\"2018-03-21T00:11:13+00:00\",\"updated_at\":\"2018-03-21T00:11:13+00:00\",\"order_product\":[{\"id\":3,\"quantity\":12},{\"id\":4,\"quantity\":16}]},{\"id\":5,\"status\":true,\"date\":\"2018-03-21T00:00:00+00:00\",\"created_at\":\"2018-03-21T01:06:33+00:00\",\"updated_at\":\"2018-03-21T01:06:33+00:00\",\"order_product\":[{\"id\":9,\"quantity\":10},{\"id\":10,\"quantity\":4}]}]";
        $json = "[{\"id\":2,\"status\":true,\"date\":\"2018-03-21T00:00:00+00:00\",\"created_at\":\"2018-03-21T00:11:13+00:00\",\"updated_at\":\"2018-03-21T00:11:13+00:00\",\"order_product\":[{\"id\":3,\"quantity\":12},{\"id\":4,\"quantity\":16}]},{\"id\":5,\"status\":true,\"date\":\"2018-03-21T00:00:00+00:00\",\"created_at\":\"2018-03-21T01:06:33+00:00\",\"updated_at\":\"2018-03-21T01:06:33+00:00\",\"order_product\":[{\"id\":9,\"quantity\":10},{\"id\":10,\"quantity\":4}]}]";
        $decodificado = json_decode($json);

        echo '<pre>';
        print_r($decodificado[0]);
        echo '</pre>';
        die();
    }


    /**
     * @Rest\Post("/api/v1/userorder", name="user_order")
     */

    public function pruebasUserOrder (Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $message = "";
        $json = $request->get('data');
        $data = json_decode($json);

        try {
            $code = 201;
            $error = false;
            $existProduct = true;
            $productError = "";

            foreach ($data->products as $idProduct => $quantity) {
                if (!$em->getRepository('App:Product')->find($idProduct)) {
                    $existProduct = false;
                    $productError = $idProduct;
                    break;
                }
            }

            if ($existProduct) {

                $order = new Order();

                foreach ($data->products as $idProduct => $quantity) {

                    if (!$em->getRepository('App:Product')->find($idProduct)) {

                        $code = 201;
                        $error = false;
                        $message = "No product found for id " . $idProduct;
                    } else {

                        $product = $em->getRepository('App:Product')->find($idProduct);
                        $orderProduct = new OrderProduct();
                        $orderProduct->setProduct($product);
                        $orderProduct->setOrder($order);
                        $orderProduct->setQuantity($quantity);

                    }

                    $status = $data->status;
                    $date = isset($data->date) ? $data->date : null;
                    //            $user->getOrder()->add($order);
                    $order->setUser($user);
                    if (!is_null($status)) {
                        $order->setStatus(true);
                    }
                    if (!is_null($date)) {
                        $order->setDate($date);
                    }
                    $em->persist($orderProduct);
                    $em->persist($order);
                    $em->flush();

                }
            } else {
                $code = 500;
                $error = true;
                $message = "No product found for id " . $productError;
            }

        } catch (Exception $ex) {
            $message = "Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code ,
            'error' => $error ,
            'data' => $code == 201 ? $order : $message ,
        ];

        return new Response($serializer->serialize($response , "json"));


        return $response;

    }


}
