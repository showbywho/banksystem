<?php

namespace App\Controller;

use App\Repository\RefundRepository;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RefundController extends AbstractController
{
    /**
     * @Route("/refund", methods = {"GET"})
     *
     * @param Request $request
     * @param RefundRepository $refund
     *
     * @return JsonResponse
     */
    public function index(Request $request, RefundRepository $refund)
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            $incomingReturn = [
                'result' => 'error',
                'msg' => "沒有存取權限,請先登入!",
            ];

            return new JsonResponse($incomingReturn);
        }

        $userRefundListData = $refund->getUserRefundList($id);
        $refundReturn = [
            'result' => 'ok',
            'msg' => '成功存取',
            'ret' => [
                'refund_data' => $userRefundListData,
            ],
        ];

        return new JsonResponse($refundReturn);
    }

    /**
     * @Route("/redisRefund", methods = {"POST"})
     *
     * @param Request $request
     * @param AdminRepository $admin
     *
     * @return JsonResponse
     */
    public function redisRefund(
        Request $request,
        AdminRepository $admin
    ) {
        $userId = $request->getSession()->get('id');
        $amount = $request->request->get('amount');

        if (!$userId) {
            $refundReturn = [
                'result' => 'error',
                'msg' => '無權限執行此操作',
            ];

            return new JsonResponse($refundReturn);
        }

        if (0 >= $amount) {
            $refundReturn = [
                'result' => 'error',
                'msg' => "提現金額($amount)不得為負數或0",
            ];

            return new JsonResponse($refundReturn);
        }

        $user = $admin->getUser($userId);
        $redis = new \Predis\Client();

        if ($user['balance'] < $amount) {

            $refundReturn = [
                'result' => 'error',
                'msg' => "餘額(" . $user['balance'] . ")不足無法提現",
            ];

            return new JsonResponse($refundReturn);
        }

        $afterBalance = $user['balance'] - intval($amount);
        $refundArray = [
            'user_id' => $userId,
            'amount' => $amount,
            'way' => 'minus',
        ];

        $serializedData = serialize($refundArray);
        $redis->rpush('order_data', $serializedData);

        $msg = '提現成功';
        $refundReturn = [
            'result' => 'ok',
            'msg' => $msg,
            'ret' => ['balance' => $afterBalance],
        ];

        return new JsonResponse($refundReturn);
    }
}
