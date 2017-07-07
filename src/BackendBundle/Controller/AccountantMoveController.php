<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\AccountantMove;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * AccountantMove controller.
 *
 * @Route("admin/accountantMove")
 */
class AccountantMoveController extends Controller
{
    /**
     * Lists all AccountantMove entities.
     *
     * @Route("/", name="accountantMove_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $AccountantMoves = $em->getRepository('BackendBundle:AccountantMove')->findAll();
        foreach ($AccountantMoves as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:AccountantMove:index.html.twig', array(
            'accountantMoves' => $AccountantMoves,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('accountantMove.title'),
            'active'=>'AccountantMove'
        ));
    }

    /**
     * Creates a new AccountantMove entity.
     *
     * @Route("/new", name="accountantMove_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $AccountantMove = new AccountantMove();
        $form = $this->createForm('BackendBundle\Form\AccountantMoveType', $AccountantMove);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($AccountantMove);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('accountantMove.create_successfull'));

            return $this->redirectToRoute('accountantMove_index');
        }

        return $this->render('BackendBundle:AccountantMove:new.html.twig', array(
            'accountantMove' => $AccountantMove,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('accountantMove.title'),
            'active'=>'AccountantMove'
        ));
    }

    /**
     * Finds and displays a AccountantMove entity.
     *
     * @Route("/{id}", name="accountantMove_show")
     * @Method("GET")
     */
    public function showAction(AccountantMove $AccountantMove)
    {
        $deleteForm = $this->createDeleteForm($AccountantMove);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:AccountantMove:show.html.twig', array(
            'accountantMove' => $AccountantMove,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('accountantMove.title'),
            'active'=>'accountantMove'
        ));
    }

    /**
     * Displays a form to edit an existing AccountantMove entity.
     *
     * @Route("/{id}/edit", name="accountantMove_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AccountantMove $AccountantMove)
    {
        $deleteForm = $this->createDeleteForm($AccountantMove);
        $editForm = $this->createForm('BackendBundle\Form\AccountantMoveType', $AccountantMove);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('accountantMove.edit_successfull'));

            return $this->redirectToRoute('accountantMove_edit', array('id' => $AccountantMove->getId()));
        }

        return $this->render('BackendBundle:AccountantMove:edit.html.twig', array(
            'accountantMove' => $AccountantMove,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('accountantMove.title'),
            'active'=>'accountantMove'
        ));
    }

    /**
     * Deletes a AccountantMove entity.
     *
     * @Route("/{id}", name="accountantMove_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AccountantMove $AccountantMove)
    {
        $form = $this->createDeleteForm($AccountantMove);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($AccountantMove);
            $em->flush();
        }

        return $this->redirectToRoute('accountantMove_index');
    }

    /**
     * Creates a form to delete a AccountantMove entity.
     *
     * @param AccountantMove $AccountantMove The AccountantMove entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AccountantMove $AccountantMove)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('accountantMove_delete', array('id' => $AccountantMove->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
