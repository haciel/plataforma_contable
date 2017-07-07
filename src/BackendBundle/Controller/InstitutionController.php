<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Institution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Institution controller.
 *
 * @Route("admin/institution")
 */
class InstitutionController extends Controller
{
    /**
     * Lists all Institution entities.
     *
     * @Route("/", name="institution_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $Institutions = $em->getRepository('BackendBundle:Institution')->findAll();
        foreach ($Institutions as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:Institution:index.html.twig', array(
            'institutions' => $Institutions,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('institution.title'),
            'active'=>'Institution'
        ));
    }

    /**
     * Creates a new Institution entity.
     *
     * @Route("/new", name="institution_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Institution = new Institution();
        $form = $this->createForm('BackendBundle\Form\InstitutionType', $Institution);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Institution);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('institution.create_successfull'));

            return $this->redirectToRoute('institution_index');
        }

        return $this->render('BackendBundle:Institution:new.html.twig', array(
            'institution' => $Institution,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('institution.title'),
            'active'=>'Institution'
        ));
    }

    /**
     * Finds and displays a Institution entity.
     *
     * @Route("/{id}", name="institution_show")
     * @Method("GET")
     */
    public function showAction(Institution $Institution)
    {
        $deleteForm = $this->createDeleteForm($Institution);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:Institution:show.html.twig', array(
            'institution' => $Institution,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('institution.title'),
            'active'=>'institution'
        ));
    }

    /**
     * Displays a form to edit an existing Institution entity.
     *
     * @Route("/{id}/edit", name="institution_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Institution $Institution)
    {
        $deleteForm = $this->createDeleteForm($Institution);
        $editForm = $this->createForm('BackendBundle\Form\InstitutionType', $Institution);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('institution.edit_successfull'));

            return $this->redirectToRoute('institution_edit', array('id' => $Institution->getId()));
        }

        return $this->render('BackendBundle:Institution:edit.html.twig', array(
            'institution' => $Institution,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('institution.title'),
            'active'=>'institution'
        ));
    }

    /**
     * Deletes a Institution entity.
     *
     * @Route("/{id}", name="institution_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Institution $Institution)
    {
        $form = $this->createDeleteForm($Institution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Institution);
            $em->flush();
        }

        return $this->redirectToRoute('institution_index');
    }

    /**
     * Creates a form to delete a Institution entity.
     *
     * @param Institution $Institution The Institution entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Institution $Institution)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('institution_delete', array('id' => $Institution->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
