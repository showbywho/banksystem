<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends AbstractController
{
    private $locationUrl = 'http://localhost:8000/login';

    /**
     * @Route("/admin", name = "admin", methods = {"GET"})
     *
     * @param object Request $request
     * @param object AdminRepository $admin
     *
     * @return array
     */
    public function index(Request $request, AdminRepository $admin): Response
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            return new RedirectResponse($this->locationUrl);
        }

        $request = $request->getSession();
        $listArray = $admin->getList($id);

        return $this->render('admin/index.html.twig', [
            'listArray' => $listArray,
            'uid' =>  $request->get('id'),
            'nickName' => $request->get('nickName'),
            'balance' => $request->get('balance'),
        ]);
    }
}
