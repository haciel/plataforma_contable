<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Operations;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Operations controller.
 *
 * @Route("admin/operations")
 */
class OperationsController extends Controller
{
    /**
     * Lists all Operations entities.
     *
     * @Route("/", name="operations_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $Operationss = $em->getRepository('BackendBundle:Operations')->findAll();
        foreach ($Operationss as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:Operations:index.html.twig', array(
            'operationss' => $Operationss,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('operations.title'),
            'active'=>'Operations'
        ));
    }

    /**
     * Creates a new Operations entity.
     *
     * @Route("/new", name="operations_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Operations = new Operations();
        $form = $this->createForm('BackendBundle\Form\OperationsType', $Operations);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Operations);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('operations.create_successfull'));

            return $this->redirectToRoute('operations_index');
        }

        return $this->render('BackendBundle:Operations:new.html.twig', array(
            'operations' => $Operations,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('operations.title'),
            'active'=>'Operations'
        ));
    }

    /**
     * Finds and displays a Operations entity.
     *
     * @Route("/{id}", name="operations_show")
     * @Method("GET")
     */
    public function showAction(Operations $Operations)
    {
        $deleteForm = $this->createDeleteForm($Operations);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:Operations:show.html.twig', array(
            'operations' => $Operations,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('operations.title'),
            'active'=>'operations'
        ));
    }

    /**
     * Displays a form to edit an existing Operations entity.
     *
     * @Route("/{id}/edit", name="operations_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Operations $Operations)
    {
        $deleteForm = $this->createDeleteForm($Operations);
        $editForm = $this->createForm('BackendBundle\Form\OperationsType', $Operations);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('operations.edit_successfull'));

            return $this->redirectToRoute('operations_edit', array('id' => $Operations->getId()));
        }

        return $this->render('BackendBundle:Operations:edit.html.twig', array(
            'operations' => $Operations,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('operations.title'),
            'active'=>'operations'
        ));
    }

    /**
     * Deletes a Operations entity.
     *
     * @Route("/{id}", name="operations_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Operations $Operations)
    {
        $form = $this->createDeleteForm($Operations);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Operations);
            $em->flush();
        }

        return $this->redirectToRoute('operations_index');
    }

    /**
     * Creates a form to delete a Operations entity.
     *
     * @param Operations $Operations The Operations entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Operations $Operations)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('operations_delete', array('id' => $Operations->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
