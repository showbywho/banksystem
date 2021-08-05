<?php

namespace App\Controller;

use App\Repository\RefundRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RefundController extends AbstractController
{
    private $locationUrl = 'http://localhost:8000/login';

    /**
     * @Route("/refund", methods = {"GET"})
     *
     * @param object Request $request
     * @param object RefundRepository $refund
     *
     * @return array
     */
    public function index(Request $request, RefundRepository $refund): Response
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            return new RedirectResponse($this->locationUrl);
        }

        $request = $request->getSession();
        $listArray = $refund->getList($id);

        return $this->render('refund/index.html.twig', [
            'listArray' => $listArray,
            'uid' =>  $request->get('id'),
            'nickName' => $request->get('nickName'),
            'balance' => $request->get('balance'),
        ]);
    }

    /**
     * @Route("/refund", methods = {"POST"})
     *
     * @param object Request $request
     * @param object RefundRepository $refund
     *
     * @return JsonResponse
     */
    public function insertRefund(Request $request, RefundRepository $refund)
    {
        $id = $request->getSession()->get('id');
        $amount = $request->request->get('amount');

        if (!$id) {
            $refundReturn = ['ret' => 'error', 'msg' => "無權限執行此操作"];

            return new JsonResponse($refundReturn);
        }

        if (0 >= $amount) {
            $refundReturn = ['ret' => 'error', 'msg' => "存款金額($amount)不得為負數或0"];

            return new JsonResponse($refundReturn);
        }

        $balanceReturn = $refund->doRefund($amount, $id, $request);

        if (isset($balanceReturn['error'])) {
            $balance = $balanceReturn['error'];
            $refundReturn = ['ret' => 'ok', 'msg' => "餘額($balance)不足無法提現"];

            return new JsonResponse($refundReturn);
        }

        $balance = $balanceReturn['success'];
        $refundReturn = ['ret' => 'ok', 'msg' => "提現成功", 'result' => ['balance' => $balance]];

        return new JsonResponse($refundReturn);
    }
}
