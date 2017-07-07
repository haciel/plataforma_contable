<?php
/**
 * Created by PhpStorm.
 * User: tomas
 * Date: 4/04/17
 * Time: 6:13
 */

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin")
 */
class HomeController extends  Controller
{
    /**
     * @Route("/", name="backend_index")
     */
    public function flashBagAction()
    {
       return $this->render('BackendBundle:Home:index.html.twig',[
           'description_page'=>$this->get('translator')->trans('home.title')
       ]);
    }
}