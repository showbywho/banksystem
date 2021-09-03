<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name = "admin", methods = {"GET"})
     *
     * @param Request $request
     * @param AdminRepository $admin
     *
     * @return JsonResponse
     */
    public function index(Request $request, AdminRepository $admin)
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            $adminReturn = [
                'result' => 'error',
                'msg' => "沒有存取權限,請先登入!",
            ];

            return new JsonResponse($adminReturn);
        }

        $userData = $admin->getUser($id);
        $adminReturn = [
            'result' => 'ok',
            'msg' => '成功存取',
            'ret' => [
                'user_data' => $userData,
            ],
        ];

        return new JsonResponse($adminReturn);
    }
}
