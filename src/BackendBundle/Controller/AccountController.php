<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Account controller.
 *
 * @Route("admin/account")
 */
class AccountController extends Controller
{
    /**
     * Lists all Account entities.
     *
     * @Route("/", name="account_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $Accounts = $em->getRepository('BackendBundle:Account')->findAll();
        foreach ($Accounts as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:Account:index.html.twig', array(
            'accounts' => $Accounts,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('account.title'),
            'active'=>'Account'
        ));
    }

    /**
     * Creates a new Account entity.
     *
     * @Route("/new", name="account_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Account = new Account();
        $form = $this->createForm('BackendBundle\Form\AccountType', $Account);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Account);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('account.create_successfull'));

            return $this->redirectToRoute('account_index');
        }

        return $this->render('BackendBundle:Account:new.html.twig', array(
            'account' => $Account,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('account.title'),
            'active'=>'Account'
        ));
    }

    /**
     * Finds and displays a Account entity.
     *
     * @Route("/{id}", name="account_show")
     * @Method("GET")
     */
    public function showAction(Account $Account)
    {
        $deleteForm = $this->createDeleteForm($Account);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:Account:show.html.twig', array(
            'account' => $Account,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('account.title'),
            'active'=>'account'
        ));
    }

    /**
     * Displays a form to edit an existing Account entity.
     *
     * @Route("/{id}/edit", name="account_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Account $Account)
    {
        $deleteForm = $this->createDeleteForm($Account);
        $editForm = $this->createForm('BackendBundle\Form\AccountType', $Account);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('account.edit_successfull'));

            return $this->redirectToRoute('account_edit', array('id' => $Account->getId()));
        }

        return $this->render('BackendBundle:Account:edit.html.twig', array(
            'account' => $Account,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('account.title'),
            'active'=>'account'
        ));
    }

    /**
     * Deletes a Account entity.
     *
     * @Route("/{id}", name="account_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Account $Account)
    {
        $form = $this->createDeleteForm($Account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Account);
            $em->flush();
        }

        return $this->redirectToRoute('account_index');
    }

    /**
     * Creates a form to delete a Account entity.
     *
     * @param Account $Account The Account entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Account $Account)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('account_delete', array('id' => $Account->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
