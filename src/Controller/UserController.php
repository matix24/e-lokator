<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\Toast;
use App\Security\AuthRole;
use App\Service\HelperCode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * Widok listy użytkowników
     * @Route("/user/index", name="user_index")
     */
    public function index() {
        return $this->render('user/index.html.twig');
    }// end index

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

    /**
     * Dodaje nowego użytkownika
     * @Route("/user/add", name="user_add")
     */
    public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'action'=>$this->generateUrl('user_add')
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $userFromForm = $form->getData();
            $userFromForm->setPassword(
                $passwordEncoder->encodePassword(
                    $userFromForm,
                    $form->get('password')->getData()
                )
            );  

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userFromForm);
            $entityManager->flush();

            $this->addFlash(Toast::SUCCESS, 'Dodano pozycję.');
            return $this->redirectToRoute('user_index'); 
        }

        return $this->render('user/add.html.twig', ['form'=>$form->createView()]);
    }// end add

    // public function 

}// end class
