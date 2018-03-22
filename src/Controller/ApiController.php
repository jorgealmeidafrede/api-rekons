<?php
/**
 * ApiController.php
 *
 * API Controller
 *
 * @category   Controller
 * @package    MyKanban
 * @author     Francisco Ugalde
 * @copyright  2018 www.franciscougalde.com
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 */

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Entity\Product;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class ApiController
 *
 * @Route("/api")
 */
class ApiController extends FOSRestController
{
    // USER URI's

    /**
     * @Rest\Post("/login_check", name="user_login_check")
     *
     * @SWG\Response(
     *     response=200,
     *     description="User was logged in successfully"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="User was not logged in successfully"
     * )
     *
     * @SWG\Parameter(
     *     name="_email",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={
     *     }
     * )
     *
     * @SWG\Parameter(
     *     name="_password",
     *     in="body",
     *     type="string",
     *     description="The password",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function getLoginCheckAction ()
    {
    }

    /**
     * @Rest\Post("/register", name="user_register")
     *
     * @SWG\Response(
     *     response=201,
     *     description="User was successfully registered"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="User was not successfully registered"
     * )
     *
     * @SWG\Parameter(
     *     name="_name",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="_email",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="_username",
     *     in="body",
     *     type="string",
     *     description="The username",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="_password",
     *     in="query",
     *     type="string",
     *     description="The password"
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function registerAction (Request $request , UserPasswordEncoderInterface $encoder)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();

        $user = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $name = $request->request->get('_name');
            $email = $request->request->get('_email');
            $address = $request->request->get('_address');
//            $username = $request->request->get('_username');
            $password = $request->request->get('_password');
            $role = $request->request->get('_role');
            $roles = explode("," , $role);


            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
//            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setRoles($roles);
            $user->setAddress($address);
            $user->setPassword($encoder->encodePassword($user , $password));

            $em->persist($user);
            $em->flush();

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to register the user - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code ,
            'error' => $error ,
            'data' => $code == 200 ? $user : $message ,
        ];

        return new Response($serializer->serialize($response , "json"));
    }

    // PRODUCTS URI's

    /**
     * @Rest\Post("/v1/product/.{_format}", name="product_add", defaults={"_format":"json"})
     * @SWG\Response(
     *     response=201,
     *     description="Product was added successfully"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error was occurred trying to add new product"
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The product name",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="description",
     *     in="body",
     *     type="string",
     *     description="The product description",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="status",
     *     in="body",
     *     type="string",
     *     description="The product status. Allowed values: Backlog, Working, Done",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="code",
     *     in="body",
     *     type="string",
     *     description="The product priority. Allowed values: High, Medium, Low",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Products")
     */

    public function addProductAction (Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $product = [];
        $message = "";
        try {
            $code = 201;
            $error = false;
            $name = $request->request->get("name" , null);
            $description = $request->request->get("description" , null);
            $status = $request->request->get("status" , null);
            $codeProduct = $request->request->get("code" , null);


            if (!is_null($name) && !is_null($description) && !is_null($status) && !is_null($codeProduct)) {
                $product = new Product();
                $product->setName($name);
                $product->setDescription($description);
                $product->setStatus($status);
                $product->setCode($codeProduct);

                $em->persist($product);
                $em->flush();

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to add new product - Error: You must to provide all the required fields";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to add new product - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code ,
            'error' => $error ,
            'data' => $code == 201 ? $product : $message ,
        ];

        return new Response($serializer->serialize($response , "json"));
    }

    /**
     * @Rest\Put("/v1/product/{id}.{_format}", name="product_edit", defaults={"_format":"json"})
     * @SWG\Response(
     *     response=201,
     *     description="The product was edited successfully"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error was occurred trying to edited product"
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The product name",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="description",
     *     in="body",
     *     type="string",
     *     description="The product description",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="status",
     *     in="body",
     *     type="string",
     *     description="The product status. Allowed values: Backlog, Working, Done",
     *     schema={}
     * )
     *
     * @SWG\Parameter(
     *     name="code",
     *     in="body",
     *     type="string",
     *     description="The product priority. Allowed values: High, Medium, Low",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Products")
     */

    public function editProductAction (Request $request , $id)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $product = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $name = $request->request->get("name" , null);
            $description = $request->request->get("description" , null);
            $status = $request->request->get("status" , null);
            $codeProduct = $request->request->get("code" , null);
            $product = $em->getRepository("App:Product")->find($id);

            if (!is_null($product)) {
                if (!is_null($name)) {
                    $product->setName($name);
                }
                if (!is_null($description)) {
                    $product->setDescription($description);
                }
                if (!is_null($status)) {
                    $product->setStatus($status);
                }
                if (!is_null($codeProduct)) {
                    $product->setCode($codeProduct);
                }
                $em->persist($product);
                $em->flush();

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to edit the current product - Error: The Product id does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to edit the current product - Error: {$ex->getMessage()}";
        }
        $response = [
            'code' => $code ,
            'error' => $error ,
            'data' => $code == 201 ? $product : $message ,
        ];

        return new Response($serializer->serialize($response , "json"));
    }

    /**
     * @Rest\Delete("/v1/product/{id}.{_format}", name="product_remove", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Product was successfully removed"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="An error was occurred trying to remove the product"
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The product ID"
     * )
     *
     * @SWG\Tag(name="Products")
     */
    public function deleteProductAction (Request $request , $id)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();

        try {
            $code = 200;
            $error = false;
            $product = $em->getRepository("App:Product")->find($id);

            if (!is_null($product)) {
                $em->remove($product);
                $em->flush();

                $message = "The product was removed successfully!";

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to remove the currrent product - Error: The product id does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to remove the current product - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code ,
            'error' => $error ,
            'data' => $message ,
        ];

        return new Response($serializer->serialize($response , "json"));
    }

    // PRODUCT LIST

    /**
     * @Rest\Get("/v1/list/product.{_format}", name="product_list_all", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets all products for current logged user."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to get all products."
     * )
     *
     * @SWG\Tag(name="Product list")
     */

    public function getAllProductAction (Request $request)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $products = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $userId = $this->getUser()->getId();
            $products = $em->getRepository("App:Product")->findAll();

            if (is_null($products)) {
                $products = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all Products - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code ,
            'error' => $error ,
            'data' => $code == 200 ? $products : $message ,
        ];

        return new Response($serializer->serialize($response , "json"));
    }

    /**
     * @Rest\Get("/v1/list/product/{id}.{_format}", name="product_list", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets product info based on passed ID parameter."
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The product with the passed ID parameter was not found or doesn't exist."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The product ID"
     * )
     *
     * @SWG\Tag(name="Product list")
     */

    public function getProductAction (Request $request , $id)
    {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $product = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $product_id = $id;
            $product = $em->getRepository("App:Product")->find($product_id);

            if (is_null($product)) {
                $code = 500;
                $error = true;
                $message = "The product does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get the current Product - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code ,
            'error' => $error ,
            'data' => $code == 200 ? $product : $message ,
        ];

        return new Response($serializer->serialize($response , "json"));
    }

    /**
     * @Rest\Get("/v1/list/productorder/{orderId}.{_format}", name="product_order_list", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets a list of products that contains an order, passing your id."
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The Order with the passed ID parameter was not found or doesn't exist."
     * )
     *
     * @SWG\Parameter(
     *     name="orderId",
     *     in="path",
     *     type="string",
     *     description="The Order ID"
     * )
     *
     * @SWG\Tag(name="Product list")
     */

    public function productsInTheOrder ($orderId)
    {
//print_r($orderId);die();
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $message = "";

        try {
            $code = 201;
            $error = false;
            $order = $em->getRepository('App:Order')->find($orderId);
            if (!is_null($order)) {
                $products = $em->getRepository('App:OrderProduct')->findProductsByOrderId($order);
            } else {
                $code = 500;
                $error = true;
                $message = "No Order found for id " . $orderId;
            }

        } catch (\Exception $ex) {
            $message = "Error: {$ex->getMessage()}";
        }
//        return new Response($serializer->serialize($products , "json"));

        $response = [
            'code' => $code ,
            'error' => $error ,
            'data' => $code == 201 ? $products : $message ,
        ];

        return new Response($serializer->serialize($response , "json"));
    }


    // ORDERS URI's

    /**
     * @Rest\POST("/v1/order.{_format}", name="order_add", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets product info based on passed ID parameter."
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The product with the passed ID parameter was not found or doesn't exist."
     * )
     *
     * @SWG\Parameter(
     *     name="status",
     *     in="body",
     *     type="string",
     *     description="The order status",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Orders")
     */
    public function addOrderdAction (Request $request)
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

                        $date = new \DateTime($date);
//                        print_r($date);die();
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

    }

}