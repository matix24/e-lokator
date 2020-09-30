<?php

namespace App\Controller\App\Partner;

use App\Entity\Partner;
use App\Service\HelperCode;
use App\Entity\Manufacturer;
use App\Entity\ManufacturerForPartner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManufacturerForPartnerController extends AbstractController 
{
    /**
     * Zwracam listę przypisanych pozycji
     * @Route("/mfp/index", name="manufacturer_for_partner_index")
     */
    public function index(){
        $em = $this->getDoctrine()->getManager();
        $partners = $em->getRepository(Partner::class)->findAll();
        $manufacturies = $em->getRepository(Manufacturer::class)->findAll();
        return $this->render('view/app/partner/manufacturer_for_partner/index.html.twig', ['partners'=>$partners, 'manufacturies'=>$manufacturies]);
    }// end index

    /**
     * Pobieram listę produktów dla DataTables
     * @Route("/mfp/listajax", name="manufacturer_for_partner_listajax", methods={"POST"})
     */
    public function listajax(Request $request){

        # sprawdzenie czy ajax
        if($request->getMethod() !== 'POST' && !$request->isXmlHttpRequest()){
            throw $this->createNotFoundException('404');
        }

        $page = ($request->get('start') <= 0 ? 1 : $request->get('start'));
        $filterBy = $request->get('filter');
        
        $em = $this->getDoctrine()->getManager();
        $paginator = $em->getRepository(ManufacturerForPartner::class)
            ->fetchForPaginator($page, $request->get('length'), $request->get('search')['value'], $request->get('filter'), $request->get('order'), $request->get('columns'));

        $total = $paginator->count();
        $resultArray = [];
        $resultArray['draw'] = $request->get('draw');
        $resultArray['recordsTotal'] = $total;
        $resultArray['recordsFiltered'] = $total;
        $resultArray['itemPerPages'] = $request->get('length');
        $resultArray['page'] = $page;
        $resultArray['data'] = [];
        $mfp = new ManufacturerForPartner();
        foreach ($paginator as $mfp) {
            $resultArray['data'][] = [
                $mfp->getIdManufacturerForPartner(),
                $mfp->getPartner()->getPartnerName(),
                $mfp->getManufacturer()->getManufacturerName(),
                $mfp->getPartner()->getPartnerDefaultMargin(),
                $mfp->getPartnerSpecialProfit(),
                HelperCode::prettyDateForDataTable($mfp->getMfpCreatedAt()),
                HelperCode::prettyDateForDataTable($mfp->getMfpUpdatedAt()),
                ""
                // '<a class="btn btn-xs btn-primary" href="'.$this->generateUrl('product_edit',['id'=>$product->getIdProduct()]).'"><i class="fas fa-edit"></i></a> '
                // .'<button class="btn btn-xs btn-danger product-to-delete" data-link="'.$this->generateUrl('product_delete',['id'=>$product->getIdProduct()]).'"><i class="fas fa-trash-alt"></i></button>'
            ];
        }

        return $this->json($resultArray, 200);

    }// end listajax

}// end class
