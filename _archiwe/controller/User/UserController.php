<?php

namespace App\Controller\App\User;

use App\Entity\User;
use App\Service\HelperCode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserController
 * @package App\Controller\App\User
 * @Security("is_granted('ROLE_CUSTOMER')")
 */
class UserController extends AbstractController
{
    
    /**
     * Lista użytkowników
     * @Route("/user/index", name="user_index")
     */
    public function index(){
        return $this->render('view/app/user/user/index.html.twig');
    }


    /**
     * Pobieram listę użytkowników dla DataTable
     * @Route("/user/listajax", name="user_listajax", methods={"POST"})
     * @param Request $request
     */
    public function listajax(Request $request){
        # sprawdzenie czy ajax
        if($request->getMethod() !== 'POST' && !$request->isXmlHttpRequest()){
            throw $this->createNotFoundException('404');
        }

        $page = ($request->get('start') <= 0 ? 1 : $request->get('start'));
        $filterBy = $request->get('filter');
        
        $em = $this->getDoctrine()->getManager();
        $paginator = $em->getRepository(User::class)
            ->fetchForPaginator($page, $request->get('length'), $request->get('search')['value'], null, $request->get('order'), $request->get('columns'));

        $total = $paginator->count();
        $resultArray = [];
        $resultArray['draw'] = $request->get('draw');
        $resultArray['recordsTotal'] = $total;
        $resultArray['recordsFiltered'] = $total;
        $resultArray['itemPerPages'] = $request->get('length');
        $resultArray['page'] = $page;
        $resultArray['data'] = [];
        foreach ($paginator as $user) {
            $resultArray['data'][] = [
                $user->getId(),
                $user->getEmail(),
                $user->getRoles(),
                $user->isVerified() ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>',
                HelperCode::prettyDateForDataTable($user->getUserCreatedAt()),
                HelperCode::prettyDateForDataTable($user->getUserUpdatedAt()),
                ""
                // '<a class="btn btn-xs btn-primary" href="'.$this->generateUrl('product_edit',['id'=>$product->getIdProduct()]).'"><i class="fas fa-edit"></i></a> '
                // .'<button class="btn btn-xs btn-danger product-to-delete" data-link="'.$this->generateUrl('product_delete',['id'=>$product->getIdProduct()]).'"><i class="fas fa-trash-alt"></i></button>'
            ];
        }

        return $this->json($resultArray, 200);
    }// end listajax

}// end class
