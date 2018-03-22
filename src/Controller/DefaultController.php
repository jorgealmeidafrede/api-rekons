<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class DefaultController extends AbstractController

{
    public function index(){
        return new Response(
            '<html><body>Lucky number: '.'index'.'</body></html>'
        );
    }

    public function apiAction()
    {
$user = $this->getUser();
var_dump(get_class_methods($user));
die();

        return new Response(
            sprintf('Logged in as %s', $this->getUser()->getName())
        );
    }

    public function api(){
        return new Response(
            '<html><body>api</body></html>'
        );
    }
}