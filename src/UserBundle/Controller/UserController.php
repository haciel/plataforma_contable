<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("admin/user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $delete_forms = array();
        $users = $em->getRepository('UserBundle:User')->findAll();
        $trans=$this->get('translator');
        foreach ($users as $entity)
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        return $this->render('UserBundle:User:index.html.twig', array(
            'entities' => $users,
            'delete_forms'=>$delete_forms,
            'description_page'=>$trans->trans('user.title')
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('UserBundle\Form\UserType', $user,[
          'province'=>$this->getDoctrine()->getRepository('BackendBundle:Province')->findAll()
        ]);
        $form->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.create','attr'=>['class'=>'btn btn-success btn-flat']]);
        $form->handleRequest($request);
        $trans=$this->get('translator');
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('user.create_successfull'));
            return $this->redirectToRoute('user_index');
        }

        return $this->render('UserBundle:User:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'description_page'=>$trans->trans('user.title')
        ));
    }



    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $city_id=$user->getCityId()?$user->getCityId()->getId():null;
        $province_id=$user->getCityId()?$user->getCityId()->getProvinceId()->getId():null;
        $province=$user->getCityId()?$user->getCityId()->getProvinceId():null;
        $editForm = $this->createForm('UserBundle\Form\UserEditType', $user,[
          'value'=>$city_id,
          'city'=>$this->getDoctrine()->getRepository('BackendBundle:City')->findBy(['provinceId'=>$province]),
          'province'=>$this->getDoctrine()->getRepository('BackendBundle:Province')->findAll(),
          'province_select'=>$province_id
        ]);
        $editForm->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType',['label'=>'backend.edit','attr'=>['class'=>'btn btn-success btn-flat']]);
        $editForm->handleRequest($request);
        $trans=$this->get('translator');
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $pass=$request->get('contrasenha');
            if($pass!='') {
                $user->setPassword($pass);
                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
            }
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('user.edit_successfull'));
            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('UserBundle:User:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'description_page'=>$trans->trans('user.title')
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
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

        $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('user.delete_successfull'));
        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' =>'backend.delete', 'attr' => array('class' => 'btn btn-sm btn-danger flat')))
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/document_upload" ,name="avatar_upload_document")
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
                    $this->get('media_resolver')->getRelRootPath('user.avatar'),
                    $filename
                );
            }
        }
        return new JsonResponse($filename);
    }
}
