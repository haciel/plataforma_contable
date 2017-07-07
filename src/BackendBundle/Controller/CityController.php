<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * City controller.
 *
 * @Route("admin/city")
 */
class CityController extends Controller
{
    /**
     * Lists all City entities.
     *
     * @Route("/", name="city_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $Citys = $em->getRepository('BackendBundle:City')->findAll();
        foreach ($Citys as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:City:index.html.twig', array(
            'citys' => $Citys,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('city.title'),
            'active'=>'City'
        ));
    }

    /**
     * Creates a new City entity.
     *
     * @Route("/new", name="city_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $City = new City();
        $form = $this->createForm('BackendBundle\Form\CityType', $City);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($City);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('city.create_successfull'));

            return $this->redirectToRoute('city_index');
        }

        return $this->render('BackendBundle:City:new.html.twig', array(
            'city' => $City,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('city.title'),
            'active'=>'City'
        ));
    }

    /**
     * Finds and displays a City entity.
     *
     * @Route("/{id}", name="city_show")
     * @Method("GET")
     */
    public function showAction(City $City)
    {
        $deleteForm = $this->createDeleteForm($City);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:City:show.html.twig', array(
            'city' => $City,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('city.title'),
            'active'=>'city'
        ));
    }

    /**
     * Displays a form to edit an existing City entity.
     *
     * @Route("/{id}/edit", name="city_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, City $City)
    {
        $deleteForm = $this->createDeleteForm($City);
        $editForm = $this->createForm('BackendBundle\Form\CityType', $City);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('city.edit_successfull'));

            return $this->redirectToRoute('city_edit', array('id' => $City->getId()));
        }

        return $this->render('BackendBundle:City:edit.html.twig', array(
            'city' => $City,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('city.title'),
            'active'=>'city'
        ));
    }

    /**
     * Deletes a City entity.
     *
     * @Route("/{id}", name="city_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, City $City)
    {
        $form = $this->createDeleteForm($City);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($City);
            $em->flush();
        }

        return $this->redirectToRoute('city_index');
    }

    /**
     * Creates a form to delete a City entity.
     *
     * @param City $City The City entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(City $City)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('city_delete', array('id' => $City->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
