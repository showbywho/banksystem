<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends AbstractController
{
    private $locationUrl = 'http://localhost:8000/login';

    /**
     * @Route("/admin", name = "admin", methods = {"GET"})
     *
     * @param Request $request
     * @param AdminRepository $admin
     *
     * @return RedirectResponse|Response
     */
    public function index(Request $request, AdminRepository $admin)
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            return new RedirectResponse($this->locationUrl);
        }

        $request = $request->getSession();
        $userData = $admin->getUser($id);

        return $this->render('admin/index.html.twig', [
            'user_data' => $userData,
            'uid' =>  $request->get('id'),
            'nick_name' => $request->get('nick_name'),
            'balance' => $request->get('balance'),
        ]);
    }
}
