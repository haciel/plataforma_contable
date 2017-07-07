<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\SlipType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * SlipType controller.
 *
 * @Route("admin/slipType")
 */
class SlipTypeController extends Controller
{
    /**
     * Lists all SlipType entities.
     *
     * @Route("/", name="slipType_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $SlipTypes = $em->getRepository('BackendBundle:SlipType')->findAll();
        foreach ($SlipTypes as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:SlipType:index.html.twig', array(
            'slipTypes' => $SlipTypes,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('slipType.title'),
            'active'=>'SlipType'
        ));
    }

    /**
     * Creates a new SlipType entity.
     *
     * @Route("/new", name="slipType_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $SlipType = new SlipType();
        $form = $this->createForm('BackendBundle\Form\SlipTypeType', $SlipType);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($SlipType);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('slipType.create_successfull'));

            return $this->redirectToRoute('slipType_index');
        }

        return $this->render('BackendBundle:SlipType:new.html.twig', array(
            'slipType' => $SlipType,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('slipType.title'),
            'active'=>'SlipType'
        ));
    }

    /**
     * Finds and displays a SlipType entity.
     *
     * @Route("/{id}", name="slipType_show")
     * @Method("GET")
     */
    public function showAction(SlipType $SlipType)
    {
        $deleteForm = $this->createDeleteForm($SlipType);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:SlipType:show.html.twig', array(
            'slipType' => $SlipType,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('slipType.title'),
            'active'=>'slipType'
        ));
    }

    /**
     * Displays a form to edit an existing SlipType entity.
     *
     * @Route("/{id}/edit", name="slipType_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SlipType $SlipType)
    {
        $deleteForm = $this->createDeleteForm($SlipType);
        $editForm = $this->createForm('BackendBundle\Form\SlipTypeType', $SlipType);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('slipType.edit_successfull'));

            return $this->redirectToRoute('slipType_edit', array('id' => $SlipType->getId()));
        }

        return $this->render('BackendBundle:SlipType:edit.html.twig', array(
            'slipType' => $SlipType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('slipType.title'),
            'active'=>'slipType'
        ));
    }

    /**
     * Deletes a SlipType entity.
     *
     * @Route("/{id}", name="slipType_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SlipType $SlipType)
    {
        $form = $this->createDeleteForm($SlipType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($SlipType);
            $em->flush();
        }

        return $this->redirectToRoute('slipType_index');
    }

    /**
     * Creates a form to delete a SlipType entity.
     *
     * @param SlipType $SlipType The SlipType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SlipType $SlipType)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('slipType_delete', array('id' => $SlipType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
