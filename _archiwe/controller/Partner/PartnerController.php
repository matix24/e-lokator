<?php

namespace App\Controller\App\Partner;

use App\Service\Toast;
use App\Entity\Partner;
use App\Service\HelperCode;
use App\Form\App\Partner\PartnerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PartnerController
 * @package App\Controller\App\Partner
 * @Security("is_granted('ROLE_CUSTOMER')")
 */
class PartnerController extends AbstractController
{
    /**
     * Zwracam listę partnerów
     * @Route("/partner/index", name="partner_index")
     */
    public function index() {
        return $this->render('view/app/partner/partner/index.html.twig');
    }// end index

    /**
     * Pobieram listę dostawców dla DataTables
     * @Route("/partner/listajax", name="partner_listajax", methods={"POST"})
     */
    public function listajax(Request $request){
        # sprawdzenie czy ajax
        if($request->getMethod() !== 'POST' && !$request->isXmlHttpRequest()){
            throw $this->createNotFoundException('404');
        }

        $page = ($request->get('start') <= 0 ? 1 : $request->get('start'));
        $filterBy = $request->get('filter');
        
        $em = $this->getDoctrine()->getManager();
        $paginator = $em->getRepository(Partner::class)
            ->fetchForPaginator($page, $request->get('length'), $request->get('search')['value'], null, $request->get('order'), $request->get('columns'));

        $total = $paginator->count();
        $resultArray = [];
        $resultArray['draw'] = $request->get('draw');
        $resultArray['recordsTotal'] = $total;
        $resultArray['recordsFiltered'] = $total;
        $resultArray['itemPerPages'] = $request->get('length');
        $resultArray['page'] = $page;
        $resultArray['data'] = [];
        foreach ($paginator as $partner) {
            $resultArray['data'][] = [
                $partner->getIdPartner(),
                $partner->getPartnerName(),
                $partner->getPartnerDefaultMargin(),
                HelperCode::prettyDateForDataTable($partner->getPartnerCreatedAt()),
                HelperCode::prettyDateForDataTable($partner->getPartnerUpdatedAt()),
                '<a class="btn btn-xs btn-primary" href="'.$this->generateUrl('partner_edit',['id'=>$partner->getIdPartner()]).'"><i class="fas fa-edit"></i></a> '
                .'<button class="btn btn-xs btn-danger partner-to-delete" data-link="'.$this->generateUrl('partner_delete',['id'=>$partner->getIdPartner()]).'"><i class="fas fa-trash-alt"></i></button>'
            ];
        }

        return $this->json($resultArray, 200);
    }// end listajax

    /**
     * Dodaje nowego partnera
     * @Route("/partner/add", name="partner_add")
     */
    public function add(Request $request){
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner, [
            'action'=>$this->generateUrl('partner_add'),
            'method'=>'POST'
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash(Toast::SUCCESS, 'Dodano pozycję.');
            return $this->redirectToRoute('partner_index');
        }

        return $this->render('view/app/partner/partner/add.html.twig', ['form'=>$form->createView()]);
    }// end add

    /**
     * Edytuje danego partnera
     * @Route("/partner/edit/{id}", name="partner_edit", requirements={"id"="\d+"})
     * @param int $id
     * @param Request $request
     */
    public function edit($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $partner = $em->getRepository(Partner::class)->find($id);

        if(is_null($partner)){
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego partnera.');
            return $this->redirectToRoute('partner_index');
        }

        $form = $this->createForm(PartnerType::class, $partner, [
            'action'=>$this->generateUrl('partner_edit', ['id'=>$id]),
            'method'=>'POST'
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash(Toast::SUCCESS, 'Zaktualizowano pozycję.');
            return $this->redirectToRoute('partner_index');
        }

        return $this->render('view/app/partner/partner/edit.html.twig', ['form'=>$form->createView()]);
    }// end edit

    /**
     * Usuwam wybranego partnera
     * @Route("/partner/delete/{id}", name="partner_delete", requirements={"id"="\d+"})
     * @param int id
     */
    public function delete($id){
        $em = $this->getDoctrine()->getManager();
        $partner = $em->getRepository(Partner::class)->find($id);

        if($partner === null){
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego partnera.');
            return $this->redirectToRoute('partner_index');
        }

        $em->remove($partner);
        $em->flush();
        $this->addFlash(Toast::SUCCESS, 'Pozycja została usunięta.');
        return $this->redirectToRoute('partner_index'); 
    }// end delete

}// end class
