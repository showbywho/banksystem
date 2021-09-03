<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", methods = {"GET"})
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $id = $request->getSession()->get('id');
        $nickName = $request->getSession()->get('nick_name');

        return $this->render('base.html.twig', [
            'id' => $id,
            'nick_name' => $nickName,
        ]);
    }

    /**
     * @Route("/login", name = "login", methods = {"POST"})
     *
     * @param Request $request
     * @param AdminRepository $admin
     *
     * @return JsonResponse
     */
    public function login(Request $request, AdminRepository $admin)
    {
        $account = $request->request->get('account');
        $password = MD5($request->request->get('password'));

        $ret = $admin->loginValidation(
            $account,
            $password,
            $request->getSession()
        );

        $jsonData = [
            'result' => 'error',
            'msg' => '帳號或密碼錯誤',
        ];

        if ($ret) {
            $jsonData = [
                'result' => 'ok',
                'msg' => '登入成功',
                'ret' => $ret,
            ];
        }

        return new JsonResponse($jsonData);
    }

    /**
     * @Route("/logout", methods = {"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request = $request->getSession();
        $request->clear();

        $ret = [
            'result' => 'ok',
            'msg' => '登出成功',
        ];

        return new JsonResponse($ret);
    }
}
