<?php

namespace App\Controller;

use App\Repository\IncomingRepository;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IncomingController extends AbstractController
{
    /**
     * @Route("/incoming", methods = {"GET"})
     *
     * @param  Request $request
     * @param  IncomingRepository $incoming
     *
     * @return JsonResponse
     */
    public function index(Request $request, IncomingRepository $incoming)
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            $incomingReturn = [
                'result' => 'error',
                'msg' => "沒有存取權限,請先登入!",
            ];

            return new JsonResponse($incomingReturn);
        }

        $request = $request->getSession();
        $userIncomingListData = $incoming->getUserIncomingList($id);
        $incomingReturn = [
            'result' => 'ok',
            'msg' => '成功存取',
            'ret' => [
                'incoming_data' => $userIncomingListData,
            ],
        ];

        return new JsonResponse($incomingReturn);
    }

    /**
     * @Route("/redisIncoming", methods = {"POST"})
     *
     * @param Request $request
     * @param AdminRepository $admin
     *
     * @return JsonResponse
     */
    public function redisIncoming(
        Request $request,
        AdminRepository $admin
    ) {
        $userId = $request->getSession()->get('id');
        $amount = $request->request->get('amount');

        if (!$userId) {
            $incomingReturn = [
                'result' => 'error',
                'msg' => '無權限執行此操作',
            ];

            return new JsonResponse($incomingReturn);
        }

        $user = $admin->getUser($userId);

        if (0 >= $amount) {
            $incomingReturn = [
                'result' => 'error',
                'msg' => "存款金額($amount)不得為負數或0",
            ];

            return new JsonResponse($incomingReturn);
        }

        $redis = new \Predis\Client();
        $afterBalance = $user['balance'] + intval($amount);
        $incomingArray = [
            'user_id' => $userId,
            'amount' => $amount,
            'way' => 'plus',
        ];

        $serializedData = serialize($incomingArray);
        $redis->rpush('order_data', $serializedData);

        $msg = '存款成功';
        $incomingReturn = [
            'result' => 'ok',
            'msg' => $msg,
            'ret' => [
                'balance' => $afterBalance,
            ],
        ];

        return new JsonResponse($incomingReturn);
    }
}
