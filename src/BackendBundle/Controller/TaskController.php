<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Task controller.
 *
 * @Route("admin/task")
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $Tasks = $em->getRepository('BackendBundle:Task')->findAll();
        foreach ($Tasks as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:Task:index.html.twig', array(
            'tasks' => $Tasks,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('task.title'),
            'active'=>'Task'
        ));
    }

    /**
     * Creates a new Task entity.
     *
     * @Route("/new", name="task_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Task = new Task();
        $form = $this->createForm('BackendBundle\Form\TaskType', $Task);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Task);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('task.create_successfull'));

            return $this->redirectToRoute('task_index');
        }

        return $this->render('BackendBundle:Task:new.html.twig', array(
            'task' => $Task,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('task.title'),
            'active'=>'Task'
        ));
    }

    /**
     * Finds and displays a Task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Task $Task)
    {
        $deleteForm = $this->createDeleteForm($Task);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:Task:show.html.twig', array(
            'task' => $Task,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('task.title'),
            'active'=>'task'
        ));
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $Task)
    {
        $deleteForm = $this->createDeleteForm($Task);
        $editForm = $this->createForm('BackendBundle\Form\TaskType', $Task);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('task.edit_successfull'));

            return $this->redirectToRoute('task_edit', array('id' => $Task->getId()));
        }

        return $this->render('BackendBundle:Task:edit.html.twig', array(
            'task' => $Task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('task.title'),
            'active'=>'task'
        ));
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Task $Task)
    {
        $form = $this->createDeleteForm($Task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Task);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * Creates a form to delete a Task entity.
     *
     * @param Task $Task The Task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $Task)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('task_delete', array('id' => $Task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
