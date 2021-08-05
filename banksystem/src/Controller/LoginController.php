<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", methods = {"GET"})
     *
     * @return array
     */
    public function index(): Response
    {
        return $this->render('login/index.html.twig', []);
    }

    /**
     * @Route("/login", name = "login", methods = {"POST"})
     *
     * @param object Request $request
     * @param object AdminRepository $admin
     *
     * @return JsonResponse
     */
    public function login(Request $request, AdminRepository $admin): Response
    {
        $account = $request->request->get('account');
        $password = MD5($request->request->get('password'));
        $result = $admin->loginValidation($account, $password, $request->getSession());
        $jsonData = ['ret' => 'error', 'msg' => '帳號或密碼錯誤'];

        if($result){
            $jsonData = ['ret' => 'ok', 'msg' => '登入成功', 'result' => $result];
        }

        return new JsonResponse($jsonData);
    }

    /**
     * @Route("/logout", methods = {"GET"})
     *
     * @param object Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): Response
    {
        $request = $request->getSession();
        $request->clear();
        $result = ['ret' => 'ok', 'msg' => '登出成功'];

        return new JsonResponse($result);
    }
}
