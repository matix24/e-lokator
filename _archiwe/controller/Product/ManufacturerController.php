<?php

namespace App\Controller\App\Product;


use App\Service\Toast;
use App\Service\HelperCode;
use App\Entity\Manufacturer;
use App\Form\App\Product\ManufacturerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ManufacturerController
 * @package App\Controller\App\Product
 * @Security("is_granted('ROLE_CUSTOMER')")
 */
class ManufacturerController extends AbstractController {

    /**
     * Pobieram listę wszystkich dostawców
     * @Route("/manufacturer/index", name="manufacturer_index")
     */
    public function index(){
        return $this->render('view/app/product/manufacturer/index.html.twig');
    } // end index


    /**
     * Pobieram listę dostawców dla DataTable
     * @Route("/manufacturer/listajax", name="manufacturer_listajax", methods={"POST"})
     */
    public function indexList(Request $request)
    {
        # sprawdzenie czy ajax
        if($request->getMethod() !== 'POST' && !$request->isXmlHttpRequest()){
            throw $this->createNotFoundException('404');
        }

        $page = ($request->get('start') <= 0 ? 1 : $request->get('start'));
        $filterBy = $request->get('filter');
        
        $em = $this->getDoctrine()->getManager();
        $paginator = $em->getRepository(Manufacturer::class)
            ->fetchForPaginator($page, $request->get('length'), $request->get('search')['value'], null, $request->get('order'), $request->get('columns'));

        $total = count($paginator);
        $resultArray = [];
        $resultArray['draw'] = $request->get('draw');
        $resultArray['recordsTotal'] = $total;
        $resultArray['recordsFiltered'] = $total;
        $resultArray['itemPerPages'] = $request->get('length');
        $resultArray['page'] = $page;
        $resultArray['data'] = [];
        foreach ($paginator as $manufacturer) {
            $resultArray['data'][] = [
                $manufacturer->getIdManufacturer(),
                $manufacturer->getManufacturerName(),
                HelperCode::prettyDateForDataTable($manufacturer->getManufacturerCreatedAt()),
                HelperCode::prettyDateForDataTable($manufacturer->getManufacturerUpdatedAt()),
                '<a class="btn btn-xs btn-primary" href="'.$this->generateUrl('manufacturer_edit',['id'=>$manufacturer->getIdManufacturer()]).'"><i class="fas fa-edit"></i></a> '
                .'<button class="btn btn-xs btn-danger manufacturer-to-delete" data-link="'.$this->generateUrl('manufacturer_delete',['id'=>$manufacturer->getIdManufacturer()]).'"><i class="fas fa-trash-alt"></i></button>'
            ];
        }

        return $this->json($resultArray, 200);
    }// end indexList


    /**
     * Dodaje nowego dostawcę
     * @Route("/manufacturer/add", name="manufacturer_add")
     */
    public function add(Request $request)
    {
        $manufacturer = new Manufacturer();
        $form = $this->createForm(ManufacturerType::class, $manufacturer, ['action' => $this->generateUrl('manufacturer_add'), 'method' => 'POST']);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash(Toast::SUCCESS, 'Dodano pozycję.');
            return $this->redirectToRoute('manufacturer_index');   
        }

        return $this->render('view/app/product/manufacturer/add.html.twig', ['form_data'=>$form->createView()]);
    }// end add


    /**
     * Edytuję aktualnego dostawcę
     * @Route("/manufacturer/edit/{id}", name="manufacturer_edit", requirements={"id"="\d+"})
     */
    public function edit($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $manufacturer = $em->getRepository(Manufacturer::class)->findById($id);

        if(is_null($manufacturer)){
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego dostawcy.');
            return $this->redirectToRoute('manufacturer_index');
        }

        $form = $this->createForm(ManufacturerType::class, $manufacturer, [
            'action'=>$this->generateUrl('manufacturer_edit', ['id'=>$id]),
            'method'=>'POST'
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash(Toast::SUCCESS, 'Zaktualizowano pozycję.');
            return $this->redirectToRoute('manufacturer_index');   
        }

        return $this->render('view/app/product/manufacturer/edit.html.twig', ['form_data'=>$form->createView()]);
    }// end edit


    /**
     * Usuwam danego dostawcę
     * @Route("/manufacturer/delete/{id}", name="manufacturer_delete", requirements={"id"="\d+"})
     * @param int $id id_manufacturer
     */
    public function delete($id){
        $em = $this->getDoctrine()->getManager();
        $manufacturer = $em->getRepository(Manufacturer::class)->find($id);

        if($manufacturer === null){
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego dostawcy.');
            return $this->redirectToRoute('manufacturer_index');
        }

        $em->remove($manufacturer);
        $em->flush();
        $this->addFlash(Toast::SUCCESS, 'Pozycja została usunięta.');
        return $this->redirectToRoute('manufacturer_index');
    }// end delete

}// end class
