<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Country controller.
 *
 * @Route("admin/country")
 */
class CountryController extends Controller
{
    /**
     * Lists all Country entities.
     *
     * @Route("/", name="country_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $Countrys = $em->getRepository('BackendBundle:Country')->findAll();
        foreach ($Countrys as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:Country:index.html.twig', array(
            'countrys' => $Countrys,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('country.title'),
            'active'=>'Country'
        ));
    }

    /**
     * Creates a new Country entity.
     *
     * @Route("/new", name="country_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Country = new Country();
        $form = $this->createForm('BackendBundle\Form\CountryType', $Country);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Country);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('country.create_successfull'));

            return $this->redirectToRoute('country_index');
        }

        return $this->render('BackendBundle:Country:new.html.twig', array(
            'country' => $Country,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('country.title'),
            'active'=>'Country'
        ));
    }

    /**
     * Finds and displays a Country entity.
     *
     * @Route("/{id}", name="country_show")
     * @Method("GET")
     */
    public function showAction(Country $Country)
    {
        $deleteForm = $this->createDeleteForm($Country);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:Country:show.html.twig', array(
            'country' => $Country,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('country.title'),
            'active'=>'country'
        ));
    }

    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="country_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Country $Country)
    {
        $deleteForm = $this->createDeleteForm($Country);
        $editForm = $this->createForm('BackendBundle\Form\CountryType', $Country);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('country.edit_successfull'));

            return $this->redirectToRoute('country_edit', array('id' => $Country->getId()));
        }

        return $this->render('BackendBundle:Country:edit.html.twig', array(
            'country' => $Country,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('country.title'),
            'active'=>'country'
        ));
    }

    /**
     * Deletes a Country entity.
     *
     * @Route("/{id}", name="country_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Country $Country)
    {
        $form = $this->createDeleteForm($Country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Country);
            $em->flush();
        }

        return $this->redirectToRoute('country_index');
    }

    /**
     * Creates a form to delete a Country entity.
     *
     * @param Country $Country The Country entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Country $Country)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('country_delete', array('id' => $Country->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
