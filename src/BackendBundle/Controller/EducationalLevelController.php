<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\EducationalLevel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * EducationalLevel controller.
 *
 * @Route("admin/educationalLevel")
 */
class EducationalLevelController extends Controller
{
    /**
     * Lists all EducationalLevel entities.
     *
     * @Route("/", name="educationalLevel_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $EducationalLevels = $em->getRepository('BackendBundle:EducationalLevel')->findAll();
        foreach ($EducationalLevels as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:EducationalLevel:index.html.twig', array(
            'educationalLevels' => $EducationalLevels,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('educationalLevel.title'),
            'active'=>'EducationalLevel'
        ));
    }

    /**
     * Creates a new EducationalLevel entity.
     *
     * @Route("/new", name="educationalLevel_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $EducationalLevel = new EducationalLevel();
        $form = $this->createForm('BackendBundle\Form\EducationalLevelType', $EducationalLevel);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($EducationalLevel);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('educationalLevel.create_successfull'));

            return $this->redirectToRoute('educationalLevel_index');
        }

        return $this->render('BackendBundle:EducationalLevel:new.html.twig', array(
            'educationalLevel' => $EducationalLevel,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('educationalLevel.title'),
            'active'=>'EducationalLevel'
        ));
    }

    /**
     * Finds and displays a EducationalLevel entity.
     *
     * @Route("/{id}", name="educationalLevel_show")
     * @Method("GET")
     */
    public function showAction(EducationalLevel $EducationalLevel)
    {
        $deleteForm = $this->createDeleteForm($EducationalLevel);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:EducationalLevel:show.html.twig', array(
            'educationalLevel' => $EducationalLevel,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('educationalLevel.title'),
            'active'=>'educationalLevel'
        ));
    }

    /**
     * Displays a form to edit an existing EducationalLevel entity.
     *
     * @Route("/{id}/edit", name="educationalLevel_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EducationalLevel $EducationalLevel)
    {
        $deleteForm = $this->createDeleteForm($EducationalLevel);
        $editForm = $this->createForm('BackendBundle\Form\EducationalLevelType', $EducationalLevel);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('educationalLevel.edit_successfull'));

            return $this->redirectToRoute('educationalLevel_edit', array('id' => $EducationalLevel->getId()));
        }

        return $this->render('BackendBundle:EducationalLevel:edit.html.twig', array(
            'educationalLevel' => $EducationalLevel,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('educationalLevel.title'),
            'active'=>'educationalLevel'
        ));
    }

    /**
     * Deletes a EducationalLevel entity.
     *
     * @Route("/{id}", name="educationalLevel_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EducationalLevel $EducationalLevel)
    {
        $form = $this->createDeleteForm($EducationalLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($EducationalLevel);
            $em->flush();
        }

        return $this->redirectToRoute('educationalLevel_index');
    }

    /**
     * Creates a form to delete a EducationalLevel entity.
     *
     * @param EducationalLevel $EducationalLevel The EducationalLevel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EducationalLevel $EducationalLevel)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('educationalLevel_delete', array('id' => $EducationalLevel->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
