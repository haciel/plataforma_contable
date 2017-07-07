<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/flash_bag", name="flash_bag", defaults={"_format"="json"})
     */
    public function flashBagAction()
    {
        return new JsonResponse($this->get('session')->getFlashBag()->all());
    }

    public function navbarRightAction()
    {
        return $this->render('BackendBundle:Default:partials/nav_right.html.twig');
    }

    public function mainSidebarAction($active)
    {

        return $this->render('BackendBundle:Default:partials/main_sidebar.html.twig',['active'=>$active]);
    }

    /**
     * @Route("/cities" ,name="get_city")
     */
    public function citiesAction(Request $request)
    {
        $province=$request->get('id_province');
        $response=[];
        if($province)
        {
            $locations=$this->getDoctrine()->getRepository('BackendBundle:City')->findBy(['provinceId'=>$province]);
            foreach($locations as $l)
            {
                $response[]=['value'=>$l->getId(),'name'=>$l->getName()];
            }
        }

        return new JsonResponse($response);
    }
}
