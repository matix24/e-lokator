<?php

namespace App\Controller\App\Product;

use App\Service\Toast;
use App\Entity\Category;
use App\Service\HelperCode;
use App\Form\App\Product\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CategoryController
 * @package App\Controller\App\Product
 * @Security("is_granted('ROLE_CUSTOMER')")
 */
class CategoryController extends AbstractController {

    /**
     * Pobieram listę wszystkich kategorii
     * @Route("/category/index", name="category_index")
     */
    public function index(){
        return $this->render('view/app/product/category/index.html.twig');
    }// end index

    /**
     * Pobieram listę wszystkich kategorii dla DataTable
     * @Route("/category/listajax", name="category_listajax", methods={"POST"})
     */
    public function indexList(Request $request){
        
        # sprawdzenie czy ajax
        if($request->getMethod() !== 'POST' && !$request->isXmlHttpRequest()){
            throw $this->createNotFoundException('404');
        }

        $page = ($request->get('start') <= 0 ? 1 : $request->get('start'));
        $filterBy = $request->get('filter');
        
        $em = $this->getDoctrine()->getManager();
        $paginator = $em->getRepository(Category::class)
            ->fetchForPaginator($page, $request->get('length'), $request->get('search')['value'], null, $request->get('order'), $request->get('columns'));

        $total = count($paginator);
        $resultArray = [];
        $resultArray['draw'] = $request->get('draw');
        $resultArray['recordsTotal'] = $total;
        $resultArray['recordsFiltered'] = $total;
        $resultArray['itemPerPages'] = $request->get('length');
        $resultArray['page'] = $page;
        $resultArray['data'] = [];
        foreach ($paginator as $category) {
            $resultArray['data'][] = [
                $category->getIdCategory(),
                $category->getCategoryName(),
                ($category->getCategoryDisabled() ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'),
                $category->getCategoryOrderBy(),
                HelperCode::prettyDateForDataTable($category->getCategoryCreatedAt()),
                HelperCode::prettyDateForDataTable($category->getCategoryUpdatedAt()),
                '<a class="btn btn-xs btn-primary" href="'.$this->generateUrl('category_edit',['id'=>$category->getIdCategory()]).'"><i class="fas fa-edit"></i></a> '
                .'<button class="btn btn-xs btn-danger category-to-delete" data-link="'.$this->generateUrl('category_delete',['id'=>$category->getIdCategory()]).'"><i class="fas fa-trash-alt"></i></button>'
            ];
        }

        return $this->json($resultArray, 200);        
    }// end indexList


    /**
     * Dodaje nową kategorię produktową
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request){
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, ['action' => $this->generateUrl('category_add'), 'method' => 'POST']);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash(Toast::SUCCESS, 'Dodano pozycję.');
            return $this->redirectToRoute('category_index');               
        }
        return $this->render('view/app/product/category/add.html.twig', ['form'=>$form->createView()]);
    }// end add

    /**
     * Edytuję aktualną kategorię
     * @Route("/category/edit/{id}", name="category_edit", requirements={"id"="\d+"})
     * @param int $id id_category
     * @param Request $request
     */
    public function edit($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);

        if(is_null($category)){
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranej kategorii.');
            return $this->redirectToRoute('category_index');
        }

        $form = $this->createForm(CategoryType::class, $category, [
            'action'=>$this->generateUrl('category_edit', ['id'=>$id]),
            'method'=>'POST'
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash(Toast::SUCCESS, 'Zaktualizowano pozycję.');
            return $this->redirectToRoute('category_index');   
        }

        return $this->render('view/app/product/category/edit.html.twig', ['form'=>$form->createView()]);
    }// end edit

    /**
     * Usuwam daną kategorię
     * @Route("/category/delete/{id}", name="category_delete", requirements={"id"="\d+"})
     * @param int $id id_category
     */
    public function delete($id){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);

        if($category === null){
            $this->addFlash(Toast::WARNING, 'Nie znaleziono wybranej kategorii.');
            return $this->redirectToRoute('category_index');
        }

        $em->remove($category);
        $em->flush();
        $this->addFlash(Toast::SUCCESS, 'Pozycja została usunięta.');
        return $this->redirectToRoute('category_index');
    }// end delete

}// end class
