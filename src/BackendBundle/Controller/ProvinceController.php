<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Province;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Province controller.
 *
 * @Route("admin/province")
 */
class ProvinceController extends Controller
{
    /**
     * Lists all Province entities.
     *
     * @Route("/", name="province_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $Provinces = $em->getRepository('BackendBundle:Province')->findAll();
        foreach ($Provinces as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:Province:index.html.twig', array(
            'provinces' => $Provinces,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('province.title'),
            'active'=>'Province'
        ));
    }

    /**
     * Creates a new Province entity.
     *
     * @Route("/new", name="province_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Province = new Province();
        $form = $this->createForm('BackendBundle\Form\ProvinceType', $Province);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Province);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('province.create_successfull'));

            return $this->redirectToRoute('province_index');
        }

        return $this->render('BackendBundle:Province:new.html.twig', array(
            'province' => $Province,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('province.title'),
            'active'=>'Province'
        ));
    }

    /**
     * Finds and displays a Province entity.
     *
     * @Route("/{id}", name="province_show")
     * @Method("GET")
     */
    public function showAction(Province $Province)
    {
        $deleteForm = $this->createDeleteForm($Province);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:Province:show.html.twig', array(
            'province' => $Province,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('province.title'),
            'active'=>'province'
        ));
    }

    /**
     * Displays a form to edit an existing Province entity.
     *
     * @Route("/{id}/edit", name="province_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Province $Province)
    {
        $deleteForm = $this->createDeleteForm($Province);
        $editForm = $this->createForm('BackendBundle\Form\ProvinceType', $Province);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('province.edit_successfull'));

            return $this->redirectToRoute('province_edit', array('id' => $Province->getId()));
        }

        return $this->render('BackendBundle:Province:edit.html.twig', array(
            'province' => $Province,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('province.title'),
            'active'=>'province'
        ));
    }

    /**
     * Deletes a Province entity.
     *
     * @Route("/{id}", name="province_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Province $Province)
    {
        $form = $this->createDeleteForm($Province);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Province);
            $em->flush();
        }

        return $this->redirectToRoute('province_index');
    }

    /**
     * Creates a form to delete a Province entity.
     *
     * @param Province $Province The Province entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Province $Province)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('province_delete', array('id' => $Province->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
