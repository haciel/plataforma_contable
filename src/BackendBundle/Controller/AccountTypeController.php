<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * AccountType controller.
 *
 * @Route("admin/accountType")
 */
class AccountTypeController extends Controller
{
    /**
     * Lists all AccountType entities.
     *
     * @Route("/", name="accountType_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $AccountTypes = $em->getRepository('BackendBundle:AccountType')->findAll();
        foreach ($AccountTypes as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:AccountType:index.html.twig', array(
            'accountTypes' => $AccountTypes,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('accountType.title'),
            'active'=>'AccountType'
        ));
    }

    /**
     * Creates a new AccountType entity.
     *
     * @Route("/new", name="accountType_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $AccountType = new AccountType();
        $form = $this->createForm('BackendBundle\Form\AccountTypeType', $AccountType);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($AccountType);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('accountType.create_successfull'));

            return $this->redirectToRoute('accountType_index');
        }

        return $this->render('BackendBundle:AccountType:new.html.twig', array(
            'accountType' => $AccountType,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('accountType.title'),
            'active'=>'AccountType'
        ));
    }

    /**
     * Finds and displays a AccountType entity.
     *
     * @Route("/{id}", name="accountType_show")
     * @Method("GET")
     */
    public function showAction(AccountType $AccountType)
    {
        $deleteForm = $this->createDeleteForm($AccountType);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:AccountType:show.html.twig', array(
            'accountType' => $AccountType,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('accountType.title'),
            'active'=>'accountType'
        ));
    }

    /**
     * Displays a form to edit an existing AccountType entity.
     *
     * @Route("/{id}/edit", name="accountType_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AccountType $AccountType)
    {
        $deleteForm = $this->createDeleteForm($AccountType);
        $editForm = $this->createForm('BackendBundle\Form\AccountTypeType', $AccountType);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('accountType.edit_successfull'));

            return $this->redirectToRoute('accountType_edit', array('id' => $AccountType->getId()));
        }

        return $this->render('BackendBundle:AccountType:edit.html.twig', array(
            'accountType' => $AccountType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('accountType.title'),
            'active'=>'accountType'
        ));
    }

    /**
     * Deletes a AccountType entity.
     *
     * @Route("/{id}", name="accountType_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AccountType $AccountType)
    {
        $form = $this->createDeleteForm($AccountType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($AccountType);
            $em->flush();
        }

        return $this->redirectToRoute('accountType_index');
    }

    /**
     * Creates a form to delete a AccountType entity.
     *
     * @param AccountType $AccountType The AccountType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AccountType $AccountType)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('accountType_delete', array('id' => $AccountType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
