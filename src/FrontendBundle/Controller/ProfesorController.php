<?php
namespace FrontendBundle\Controller;

use BackendBundle\Entity\AnswerTask;
use BackendBundle\Entity\Company;
use BackendBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class ProfesorController extends Controller
{

    /**
     * @Route("/profesor/{id}/listado/estudiantes", name="profesor")
     */
    public function indexAction(Company $company)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $estudiantes = $em->getRepository('UserBundle:User')->findBy(array(
            'institutionId' => $user->getInstitutionId(),
            'educationallevelId' => $user->getEducationallevelId(),
        ));
        foreach ($estudiantes as $index => $estudiante) {
            if (!in_array('ROLE_ESTUDENT', $estudiante->getRoles())) {
                unset($estudiantes[$index]);
            }
        }
        $delete_forms = array();
        foreach ($estudiantes as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        $breadcrumb = array();
        $breadcrumb[] = array(
            'name' => 'Inicio',
            'url' => $this->container->get('router')->generate('plataformaEducativa'),
        );
        $breadcrumb[] = array(
            'name' => $company->getName(),
            'url' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId())),
        );
        return $this->render('FrontendBundle:Profesor:index.html.twig', array(
            'estudiantes' => $estudiantes,
            'breadcrumb' => $breadcrumb,
            'delete_forms' => $delete_forms,
            'company' => $company,
            'close' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId()))
        ));
    }

    /**
     * @Route("/profesor/{id}/listado/tareas", name="profesor_tareas")
     */
    public function tareasAction(Request $request, Company $company)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $tareas = $em->getRepository('BackendBundle:Task')->findBy(array(
            'userId' => $user,
        ));
        $delete_forms = array();
        foreach ($tareas as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteFormTask($entity)->createView();

        $task = new Task();
        $task->setUserId($user);
        $task->setInstitutionId($user->getInstitutionId());
        $task->setEducationallevelId($user->getEducationallevelId());
        $form = $this->createForm('FrontendBundle\Form\TaskType', $task);
        $form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-success flat']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('task.create_successfull'));
            return $this->redirectToRoute('profesor_tareas', array('id' => $company->getId()));
        }
        $breadcrumb = array();
        $breadcrumb[] = array(
            'name' => 'Inicio',
            'url' => $this->container->get('router')->generate('plataformaEducativa'),
        );
        $breadcrumb[] = array(
            'name' => $company->getName(),
            'url' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId())),
        );
        return $this->render('FrontendBundle:Profesor:tasks.html.twig', array(
            'tareas' => $tareas,
            'breadcrumb' => $breadcrumb,
            'delete_forms' => $delete_forms,
            'active' => 'task',
            'form' => $form->createView(),
            'company' => $company,
            'close' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId()))
        ));
    }

    public function is_access(User $user)
    {
        $account = $this->container->get('security.context')->getToken()->getUser();
        if ($account->getId() != $user->getId()) {
            throw $this->createAccessDeniedException('No tiene permisos para acceder a esta página!');
        }
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/profesor/editar/tarea/{company}/{id}", name="profesor_editar_tarea",requirements={"id" = "\d+"}, defaults={"company" = null})
     * @Method({"GET", "POST"})
     */
    public function editTaskAction(Request $request, Company $company, Task $tarea)
    {
        $this->is_access($tarea->getUserId());
        $editForm = $this->createForm('FrontendBundle\Form\TaskType', $tarea);
        $editForm->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-success flat']]);

        $editForm->handleRequest($request);
        $trans = $this->get('translator');

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('task.edit_successfull'));

            return $this->redirectToRoute('profesor_tareas', array('id' => $company->getId()));

        }
        $breadcrumb = array();
        $breadcrumb[] = array(
            'name' => 'Inicio',
            'url' => $this->container->get('router')->generate('plataformaEducativa'),
        );
        $breadcrumb[] = array(
            'name' => $company->getName(),
            'url' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId())),
        );
        $breadcrumb[] = array(
            'name' => 'Tareas',
            'url' => $this->container->get('router')->generate('profesor_tareas', array('id' => $company->getId())),
        );
        return $this->render('FrontendBundle:Profesor:formTask.html.twig', array(
            'task' => $tarea,
            'title' => 'Editar Tarea',
            'breadcrumb' => $breadcrumb,
            'form' => $editForm->createView(),
            'description_page' => $trans->trans('task.name'),
            'close' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId())),
            'active' => 'task'
        ));
    }

    /**
     * @Route("/profesor/activar/estudiante/{id}", name="profesor_activar_estudiante")
     */
    public function activarEstudianteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(true);
        $em->persist($user);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'El estudiante ' . $user->getName() . ' ' . $user->getLastname() . ' ha sido activado.');
        return $this->redirectToRoute('profesor');
    }

    /**
     * @Route("/profesor/desactivar/estudiante/{id}", name="profesor_desactivar_estudiante")
     */
    public function desactivarEstudianteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(false);
        $em->persist($user);
        $em->flush();
        $this->get('session')->getFlashBag()->add('warning', 'El estudiante ' . $user->getName() . ' ' . $user->getLastname() . ' ha sido desactivado.');
        return $this->redirectToRoute('profesor');
    }

    /**
     * Creates a form to delete a Company entity.
     *
     * @param Company $Company The Company entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => 'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('profesor_delete_estudiante', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function createDeleteFormTask(Task $task)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => 'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('profesor_delete_task', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function createDeleteFormAnswer(AnswerTask $answerTask)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => 'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('profesor_delete_answer', array('id' => $answerTask->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}/profesor-delete-estudiante", name="profesor_delete_estudiante")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('profesor');
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}/profesor-delete-tarea", name="profesor_delete_task")
     * @Method("DELETE")
     */
    public function deleteTaskAction(Request $request, Task $task)
    {
        $form = $this->createDeleteFormTask($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('profesor_tareas');
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}/profesor-delete-respuesta", name="profesor_delete_answer")
     * @Method("DELETE")
     */
    public function deleteAnswerAction(Request $request, AnswerTask $answerTask)
    {
        $form = $this->createDeleteFormAnswer($answerTask);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($answerTask);
            $em->flush();
        }

        return $this->redirectToRoute('profesor_revision');
    }

    /**
     *
     * @Route("/profesor/{id}/listado/revisiones", name="profesor_revision")
     *
     */
    public function answerTaskAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $findEstudent = $request->get('estudiante');
        $findTask = $request->get('tarea');
        $AnswerTasks = $em->getRepository('BackendBundle:AnswerTask')->findAll();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $respuestas = array();
        foreach ($AnswerTasks as $answerTask) {
            if ($answerTask->getUserId()->getInstitutionId() == $user->getInstitutionId() &&
                $answerTask->getUserId()->getEducationallevelId() == $user->getEducationallevelId() &&
                $this->findAnswer($findEstudent, $findTask, $answerTask)
            ) {
                $respuestas[] = $answerTask;
            }
        }
        $delete_forms = array();
        foreach ($AnswerTasks as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteFormAnswer($entity)->createView();

        $answer_id = $request->get('answer_id');
        $calificacion = $request->get('calificacion');
        if(!empty($answer_id)){
            $answer=$em->getRepository('BackendBundle:AnswerTask')->find($answer_id);
            $answer->setNota($calificacion);
            $em->persist($answer);
            $em->flush();
        }

        $breadcrumb = array();
        $breadcrumb[] = array(
            'name' => 'Inicio',
            'url' => $this->container->get('router')->generate('plataformaEducativa'),
        );
        $breadcrumb[] = array(
            'name' => $company->getName(),
            'url' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId())),
        );

        $tareas = $em->getRepository('BackendBundle:Task')->findBy(array('userId' => $user));
        return $this->render('FrontendBundle:Profesor:answerTask.html.twig', array(
            'answerTasks' => $respuestas,
            'delete_forms' => $delete_forms,
            'tareas' => $tareas,
            'breadcrumb' => $breadcrumb,
            'findEstudent' => $findEstudent,
            'findTask' => $findTask,
            'company' => $company,
            'close' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId()))
        ));
    }

    public function findAnswer($findEstudent, $findTask, AnswerTask $answerTask)
    {
        if ($findEstudent != '') {
            $fullname = $answerTask->getUserId()->getName() . $answerTask->getUserId()->getLastname();
            $aux = str_replace($findEstudent, '', $fullname);
            if ($fullname != $aux) {
                if ($findTask != '') {
                    return $answerTask->getTaskId()->getId() == $findTask;
                }
                return true;
            }
            return false;
        } else if ($findTask != '') {
            return $answerTask->getTaskId()->getId() == $findTask;
        }
        return true;
    }
 }