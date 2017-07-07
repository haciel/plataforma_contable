<?php
namespace FrontendBundle\Controller;

use BackendBundle\Entity\AccountantMove;
use BackendBundle\Entity\Company;
use BackendBundle\Entity\Account;
use BackendBundle\Entity\Operations;
use Proxies\__CG__\BackendBundle\Entity\SlipType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class MovimientosController extends Controller
{
    /**
     * @Route("/movimientos-contables/{id}", name="movimientosContables_ver")
     */
    public function indexAction(Company $company, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->is_access($company->getUserId());
        $movimientos = $em->getRepository('BackendBundle:AccountantMove')->findBy(array(
            'companyId' => $company,
        ));

        $delete_forms = array();
        foreach ($movimientos as $entity) {
            $delete_forms[$entity->getId()] = $this->createDeleteForm($entity)->createView();
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
        return $this->render('FrontendBundle:Movimientos:index.html.twig', array(
            'empresa' => $company,
            'movimientos' => $movimientos,
            'delete_forms' => $delete_forms,
            'breadcrumb' => $breadcrumb,
            'close'=>$this->container->get('router')->generate('empresa_ver',array('id'=>$company->getId()))
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
     * @Route("/movimiento-contable-buscar/{id}/", name="movimientosContables_buscar")
     */
    public function findAction(Request $request,Company $company){
        $search=$request->get('search');
        $em = $this->getDoctrine()->getManager();
        $idC=$company->getId();
        $movimientos = $em->createQuery("SELECT m FROM BackendBundle:AccountantMove m WHERE m.companyId=$idC AND m.description LIKE '%$search%'")->getResult();
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
            'name' => 'Nuevo Movimiento Contable',
            'url' => $this->container->get('router')->generate('movimientosContables_crear', array('id' => $company->getId())),
        );
        return $this->render('FrontendBundle:Movimientos:search.html.twig', array(
            'empresa' => $company,
            'search'=>$search,
            'movimientos'=>$movimientos,
            'breadcrumb' => $breadcrumb,
            'close'=>$this->container->get('router')->generate('movimientosContables_crear',array('id'=>$company->getId()))
        ));
    }

    /**
     * @Route("/movimiento-contable-nuevo/{id}", name="movimientosContables_crear")
     */
    public function newAction(Company $company, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->is_access($company->getUserId());

        $number = 1;
        $movimiento = new AccountantMove();
        $movimiento->setCompanyId($company);
        $movimiento->setDate(new \DateTime('now'));
        for($i=0;$i<6;$i++){
            $operation = new Operations();
            $operation->setDeve(0);
            $operation->setHaber(0);
            $movimiento->getOperations()->add($operation);
        }
        $movimientos = $em->getRepository('BackendBundle:AccountantMove')->findBy(array('companyId'=>$company));
        $number += count($movimientos);
        $movimiento->setNumber($number);
        $form = $this->createForm('FrontendBundle\Form\AccountantMoveType', $movimiento);
        $form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
            'label' => 'Guardar',
            'attr' => ['class' => 'btn btn-success hidden flat'],
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($movimiento->getOperations() as &$operation) {
                $operation->setAccountmoveId($movimiento);
                $account=$operation->getAccountId();
                if(empty($account)){
                    $movimiento->removeOperation($operation);
                }
            }
            $em->persist($movimiento);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('accountantMove.create_successfull'));
            return $this->redirectToRoute('movimientosContables_crear', array('id' => $company->getId()));
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
            'name' => 'Movimientos Contables',
            'url' => $this->container->get('router')->generate('movimientosContables_ver', array('id' => $company->getId())),
        );
        return $this->render('FrontendBundle:Movimientos:show.html.twig', array(
            'empresa' => $company,
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
            'close'=>$this->container->get('router')->generate('movimientosContables_ver', array('id' => $company->getId()))
        ));
    }

    /**
     * @Route("/movimiento-contable/{id}", name="movimientoContable_show")
     */
    public function showAction(AccountantMove $movimiento, Request $request)
    {
        $this->is_access($movimiento->getCompanyId()->getUserId());

        $em = $this->getDoctrine()->getManager();
        $company = $movimiento->getCompanyId();
        $number = $movimiento->getId();
        $form = $this->createForm('FrontendBundle\Form\AccountantMoveType', $movimiento);
        $form->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
            'label' => 'Guardar',
            'attr' => ['class' => 'btn btn-success hidden flat'],
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($movimiento->getOperations() as &$operation) {
                $operation->setAccountmoveId($movimiento);
            }
            $em->persist($movimiento);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('accountantMove.create_successfull'));
            return $this->redirectToRoute('movimientoContable_show', array('id' => $movimiento->getId()));
        }
        $breadcrumb[] = array(
            'name' => 'Inicio',
            'url' => $this->container->get('router')->generate('plataformaEducativa'),
        );
        $breadcrumb[] = array(
            'name' => $company->getName(),
            'url' => $this->container->get('router')->generate('empresa_ver', array('id' => $company->getId())),
        );
        $breadcrumb[] = array(
            'name' => 'Movimientos Contables',
            'url' => $this->container->get('router')->generate('movimientosContables_ver', array('id' => $company->getId())),
        );
        return $this->render('FrontendBundle:Movimientos:show.html.twig', array(
            'empresa' => $company,
            'numero' => $number,
            'breadcrumb' => $breadcrumb,
            'form' => $form->createView(),
            'close'=>$this->container->get('router')->generate('movimientosContables_ver', array('id' => $company->getId()))
        ));
    }

    public function getFather($cuentas,Account $cuenta){
        $father=$cuenta;
        $codeFather=$cuenta->getCode()."";
        $code=$cuenta->getCode()."";
        foreach($cuentas as $item){
            /** @var Account $item */
            $codeAux=$item->getCode()."";
            if(strlen($codeAux)<strlen($code)){
                $ok=true;
                for($i=0;$i<strlen($codeAux);$i++){
                    if($codeAux[$i]!=$code[$i]){
                        $ok=false;
                    }
                }
                if($ok && $codeFather>$codeAux){
                    $codeFather=$codeAux;
                    $father=$item;
                }
            }
        }
        return $father;
    }

    /**
     * @Route("/get-accounts/{id}", name="getAccounts")
     */
    public function getAccountName(Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $cuentasAll = $em->getRepository('BackendBundle:Account')->findBy(array(
            'companyId' => $company,
        ));
        $order=$this->orderString($cuentasAll);
        $cuentas=array();
        foreach ($order as $item){
            $cuentas[]=$cuentasAll[$item['index']];
        }
        $response = array();
        foreach ($cuentas as $item) {
            $response[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'code' => $item->getCode(),
            );
        }
        return new JsonResponse($response);
    }

    public function orderString($accounts){
        $data=array();
        $maxLength=0;
        foreach ($accounts as $account){
            if($maxLength<strlen($account->getCode())){
                $maxLength=strlen($account->getCode());
            }
        }
        for($i=0;$i<count($accounts);$i++){
            $code=$accounts[$i]->getCode();
            $multiplo=$maxLength-strlen($accounts[$i]->getCode())?pow(10,$maxLength-strlen($accounts[$i]->getCode())):1;
            $data[]=array(
                'code'=>$multiplo!=1?$code*$multiplo:$code+1,
                'index'=>$i);
        }
        sort($data);
        return $data;
    }
    /**
     * @Route("/get-slipNumber/{id}", name="getSlipNumber")
     */
    public function getSlipNumber(Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $slipTypes=$em->getRepository('BackendBundle:SlipType')->findAll();
        $response=array();
        foreach($slipTypes as $slipType){
            $asientos = $em->getRepository('BackendBundle:AccountantMove')->findBy(array(
              'slipeId' => $slipType,
              'companyId' => $company,
            ));
            $response[$slipType->getId()]=count($asientos)+1;
        }

        return new JsonResponse($response);
    }

    /**
     * Deletes a AccountantMove entity.
     *
     * @Route("/movimiento-contable-eliminar/{id}", name="movimientoContable_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AccountantMove $AccountantMove)
    {
        $this->is_access($AccountantMove->getCompanyId()->getUserId());

        $form = $this->createDeleteForm($AccountantMove);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($AccountantMove);
            $em->flush();
        }

        return $this->redirectToRoute('movimientosContables_ver', array('id' => $AccountantMove->getCompanyId()->getId()));
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
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array(
                'label' => 'backend.delete',
                'attr' => array('class' => 'btn btn-sm btn-danger flat'),
            ))
            ->setAction($this->generateUrl('movimientoContable_delete', array('id' => $AccountantMove->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}