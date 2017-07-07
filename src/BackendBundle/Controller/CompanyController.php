<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Company controller.
 *
 * @Route("admin/company")
 */
class CompanyController extends Controller
{
    /**
     * Lists all Company entities.
     *
     * @Route("/", name="company_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $Companys = $em->getRepository('BackendBundle:Company')->findAll();
        foreach ($Companys as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:Company:index.html.twig', array(
            'companys' => $Companys,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('company.title'),
            'active'=>'Company'
        ));
    }

    /**
     * Creates a new Company entity.
     *
     * @Route("/new", name="company_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Company = new Company();
        $form = $this->createForm('BackendBundle\Form\CompanyType', $Company);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Company);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('company.create_successfull'));

            return $this->redirectToRoute('company_index');
        }

        return $this->render('BackendBundle:Company:new.html.twig', array(
            'company' => $Company,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('company.title'),
            'active'=>'Company'
        ));
    }

    /**
     * Finds and displays a Company entity.
     *
     * @Route("/{id}", name="company_show")
     * @Method("GET")
     */
    public function showAction(Company $Company)
    {
        $deleteForm = $this->createDeleteForm($Company);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:Company:show.html.twig', array(
            'company' => $Company,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('company.title'),
            'active'=>'company'
        ));
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/{id}/edit", name="company_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Company $Company)
    {
        $deleteForm = $this->createDeleteForm($Company);
        $editForm = $this->createForm('BackendBundle\Form\CompanyType', $Company);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('company.edit_successfull'));

            return $this->redirectToRoute('company_edit', array('id' => $Company->getId()));
        }

        return $this->render('BackendBundle:Company:edit.html.twig', array(
            'company' => $Company,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('company.title'),
            'active'=>'company'
        ));
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}", name="company_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Company $Company)
    {
        $form = $this->createDeleteForm($Company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Company);
            $em->flush();
        }

        return $this->redirectToRoute('company_index');
    }

    /**
     * Creates a form to delete a Company entity.
     *
     * @param Company $Company The Company entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Company $Company)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('company_delete', array('id' => $Company->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
