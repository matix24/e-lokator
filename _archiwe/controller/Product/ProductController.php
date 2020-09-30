<?php

namespace App\Controller\App\Product;

use App\Service\Toast;
use App\Entity\Product;
use App\Entity\Category;
use App\Service\HelperCode;
use App\Entity\Manufacturer;
use App\Form\App\Product\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProductController
 * @package App\Controller\App\Product
 * @Security("is_granted('ROLE_CUSTOMER')")
 */
class ProductController extends AbstractController {

    /**
     * Zwracam listę produktów
     * @Route("/product/index", name="product_index")
     */
    public function index() {

        #kategorie do filtrowania
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();
        $manufacturies = $em->getRepository(Manufacturer::class)->findAll();
        return $this->render('view/app/product/product/index.html.twig', ['categories'=>$categories, 'manufacturies'=>$manufacturies]);
    }// end index

    /**
     * Pobieram listę produktów dla DataTable
     * @Route("/product/listajax", name="product_listajax", methods={"POST"})
     */
    public function listajax(Request $request){
        # sprawdzenie czy ajax
        if($request->getMethod() !== 'POST' && !$request->isXmlHttpRequest()){
            throw $this->createNotFoundException('404');
        }

        $page = ($request->get('start') <= 0 ? 1 : $request->get('start'));
        $filterBy = $request->get('filter');
        
        $em = $this->getDoctrine()->getManager();
        $paginator = $em->getRepository(Product::class)
            ->fetchForPaginator($page, $request->get('length'), $request->get('search')['value'], $request->get('filter'), $request->get('order'), $request->get('columns'));

        $total = $paginator->count();
        $resultArray = [];
        $resultArray['draw'] = $request->get('draw');
        $resultArray['recordsTotal'] = $total;
        $resultArray['recordsFiltered'] = $total;
        $resultArray['itemPerPages'] = $request->get('length');
        $resultArray['page'] = $page;
        $resultArray['data'] = [];
        foreach ($paginator as $product) {
            $resultArray['data'][] = [
                $product->getIdProduct(),
                $product->getProductManufacturerSymbol(),
                $product->getProductName(),
                $product->getProductDescription(),
                $product->getCategory()->getCategoryName(),
                $product->getManufacturer()->getManufacturerName(),
                $product->getProductManufacturerPrice(),
                HelperCode::prettyDateForDataTable($product->getProductCreatedAt()),
                HelperCode::prettyDateForDataTable($product->getProductUpdatedAt()),
                '<a class="btn btn-xs btn-primary" href="'.$this->generateUrl('product_edit',['id'=>$product->getIdProduct()]).'"><i class="fas fa-edit"></i></a> '
                .'<button class="btn btn-xs btn-danger product-to-delete" data-link="'.$this->generateUrl('product_delete',['id'=>$product->getIdProduct()]).'"><i class="fas fa-trash-alt"></i></button>'
            ];
        }

        return $this->json($resultArray, 200);
    }// end listajax

    /**
     * Dodaje nowy produkt do bazy
     * @Route("/product/add", name="product_add")
     */
    public function add(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'action'=>$this->generateUrl('product_add'),
            'method'=>'POST'
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash(Toast::SUCCESS, 'Dodano pozycję.');
            return $this->redirectToRoute('product_index'); 
        }

        return $this->render('view/app/product/product/add.html.twig', ['form'=>$form->createView()]);
    }// end add


    /**
     * Edytuje dany produkt
     * @Route("/product/edit/{id}", name="product_edit", requirements={"id"="\d+"})
     * @param int $id
     * @param Request $request
     */
    public function edit($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        if(is_null($product)){
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego produktu.');
            return $this->redirectToRoute('product_index');
        }

        $form = $this->createForm(ProductType::class, $product, [
            'action'=>$this->generateUrl('product_edit', ['id'=>$id]),
            'method'=>'POST'
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash(Toast::SUCCESS, 'Zaktualizowano pozycję.');
            return $this->redirectToRoute('product_index');
        }

        return $this->render('view/app/product/product/edit.html.twig', ['form'=>$form->createView()]);
    }// end edit

    /**
     * Usuwam dany produkt
     * @Route("/product/delete/{id}", name="product_delete", requirements={"id"="\d+"})
     * @param int $id
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        if($product === null){
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranego produktu.');
            return $this->redirectToRoute('product_index');
        }

        $em->remove($product);
        $em->flush();
        $this->addFlash(Toast::SUCCESS, 'Pozycja została usunięta.');
        return $this->redirectToRoute('product_index');        
    }// end delete

}// end class
