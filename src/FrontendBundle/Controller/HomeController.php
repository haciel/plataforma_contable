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

class HomeController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {

        return $this->render('FrontendBundle:Index:index.html.twig', array(
            'active' => '',
        ));
    }

    /**
     * @Route("/plataforma-educativa", name="plataformaEducativa")
     * @Method({"GET", "POST"})
     */
    public function plataformaEducativaAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (in_array('ROLE_ESTUDENT', $user->getRoles())) {
            return $this->estudiante($request);
        } else {
            return $this->profesor();
        }
    }


    private function estudiante(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $empresas = $em->getRepository('BackendBundle:Company')->findBy(array(
            'userId' => $user,
        ));

        $answer=new AnswerTask();
        $answer->setUserId($user);
        $answer->setDate(new \DateTime('now'));
        $form = $this->createForm('FrontendBundle\Form\AnswerTaskType', $answer);
        $form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
            'label' => 'Guardar',
            'attr' => ['class' => 'btn btn-success flat'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Su respuesta ha sido enviada.');
            return $this->redirectToRoute('plataformaEducativa');
        }
        $tareas_pendientes = $this->getTareasPendientes($user);
        $delete_forms = array();
        foreach ($empresas as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        return $this->render('FrontendBundle:Home:index.html.twig', array(
            'empresas' => $empresas,
            'tareas' => $tareas_pendientes,
            'delete_forms' => $delete_forms,
            'active' => '',
            'form'=>$form->createView(),
        ));
    }

    private function profesor()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $empresas = $em->getRepository('BackendBundle:Company')->findBy(array(
            'userId' => $user,
        ));
        $delete_forms = array();
        foreach ($empresas as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        return $this->render('FrontendBundle:Home:index.html.twig', array(
            'empresas' => $empresas,
            'delete_forms' => $delete_forms,
            'active' => '',
        ));
    }

    private function getTareasPendientes($user)
    {
        $em = $this->getDoctrine()->getManager();
        $tareas = $em->getRepository('BackendBundle:Task')->findBy(array(
            'institutionId' => $user->getInstitutionId(),
            'educationallevelId' => $user->getEducationallevelId(),
        ));
        $response=array();
        foreach ($tareas as $tarea){
            $hoy=new \DateTime('now');
            if($tarea->getDateLimit()->format('Y-m-d')>=$hoy->format('Y-m-d')){
                $respuestas = $em->getRepository('BackendBundle:AnswerTask')->findBy(array(
                    'userId' => $user,
                    'taskId'=>$tarea
                ));
                if(count($respuestas)==0){
                    $response[]=$tarea;
                }
            }
        }
        return $response;
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
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => 'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('company_delete_estudiante', array('id' => $Company->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}