<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\AnswerTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * AnswerTask controller.
 *
 * @Route("admin/answerTask")
 */
class AnswerTaskController extends Controller
{
    /**
     * Lists all AnswerTask entities.
     *
     * @Route("/", name="answerTask_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();

        $AnswerTasks = $em->getRepository('BackendBundle:AnswerTask')->findAll();
        foreach ($AnswerTasks as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $trans=$this->get('translator');
        return $this->render('BackendBundle:AnswerTask:index.html.twig', array(
            'answerTasks' => $AnswerTasks,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('answerTask.title'),
            'active'=>'AnswerTask'
        ));
    }

    /**
     * Creates a new AnswerTask entity.
     *
     * @Route("/new", name="answerTask_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $AnswerTask = new AnswerTask();
        $form = $this->createForm('BackendBundle\Form\AnswerTaskType', $AnswerTask);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);

        $form->handleRequest($request);
        $trans=$this->get('translator');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($AnswerTask);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('answerTask.create_successfull'));

            return $this->redirectToRoute('answerTask_index');
        }

        return $this->render('BackendBundle:AnswerTask:new.html.twig', array(
            'answerTask' => $AnswerTask,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('answerTask.title'),
            'active'=>'AnswerTask'
        ));
    }

    /**
     * Finds and displays a AnswerTask entity.
     *
     * @Route("/{id}", name="answerTask_show")
     * @Method("GET")
     */
    public function showAction(AnswerTask $AnswerTask)
    {
        $deleteForm = $this->createDeleteForm($AnswerTask);
        $trans=$this->get('translator');

        return $this->render('BackendBundle:AnswerTask:show.html.twig', array(
            'answerTask' => $AnswerTask,
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('answerTask.title'),
            'active'=>'answerTask'
        ));
    }

    /**
     * Displays a form to edit an existing AnswerTask entity.
     *
     * @Route("/{id}/edit", name="answerTask_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AnswerTask $AnswerTask)
    {
        $deleteForm = $this->createDeleteForm($AnswerTask);
        $editForm = $this->createForm('BackendBundle\Form\AnswerTaskType', $AnswerTask);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);

        $editForm->handleRequest($request);
        $trans=$this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('answerTask.edit_successfull'));

            return $this->redirectToRoute('answerTask_edit', array('id' => $AnswerTask->getId()));
        }

        return $this->render('BackendBundle:AnswerTask:edit.html.twig', array(
            'answerTask' => $AnswerTask,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('answerTask.title'),
            'active'=>'answerTask'
        ));
    }

    /**
     * Deletes a AnswerTask entity.
     *
     * @Route("/{id}", name="answerTask_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AnswerTask $AnswerTask)
    {
        $form = $this->createDeleteForm($AnswerTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($AnswerTask);
            $em->flush();
        }

        return $this->redirectToRoute('answerTask_index');
    }

    /**
     * Creates a form to delete a AnswerTask entity.
     *
     * @param AnswerTask $AnswerTask The AnswerTask entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AnswerTask $AnswerTask)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('answerTask_delete', array('id' => $AnswerTask->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/document_upload" ,name="upload_document")
     */
    public function documentUploadAction(Request $request)
    {

        $filename = null;
        $files_upload=$request->files->getIterator();
        foreach($files_upload as $k=>$i)
        {
            $file=$request->files->get($k);
            if($file) {
                $filename= uniqid(rand(), true).'.'.$file->getClientOriginalExtension();
                $file->move(
                    $this->get('media_resolver')->getRelRootPath('answerTask.files'),
                    $filename
                );
            }
        }
        return new JsonResponse($filename);
    }
}
