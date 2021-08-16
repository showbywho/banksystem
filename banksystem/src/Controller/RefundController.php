<?php

namespace App\Controller;

use App\Entity\Refund;
use App\Repository\RefundRepository;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param Request $request
     * @param RefundRepository $refund
     *
     * @return RedirectResponse|Response
     */
    public function index(Request $request, RefundRepository $refund)
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            return new RedirectResponse($this->locationUrl);
        }

        $request = $request->getSession();
        $userRefundListData = $refund->getUserRefundList($id);

        return $this->render('refund/index.html.twig', [
            'user_refund_list_data' => $userRefundListData,
            'uid' =>  $request->get('id'),
            'nick_name' => $request->get('nick_name'),
            'balance' => $request->get('balance'),
        ]);
    }

    /**
     * @Route("/redisRefund", methods = {"POST"})
     *
     * @param Request $request
     * @param AdminRepository $admin
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function redisRefund(
        Request $request,
        AdminRepository $admin,
        EntityManagerInterface $em
    ) {
        $userId = $request->getSession()->get('id');
        $amount = $request->request->get('amount');
        $user = $admin->getUser($userId);

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
                'msg' => "存款金額($amount)不得為負數或0",
            ];

            return new JsonResponse($refundReturn);
        }

        $tradeNo = 'tradeNO' . strtotime('now') . rand(100, 999);
        $redis = new \Predis\Client();
        $beforeAmount = $redis->get("user:$userId");

        if (($user['balance'] + $beforeAmount) < $amount) {

            $refundReturn = [
                'result' => 'error',
                'msg' => "餘額(" . $user['balance'] . ")不足無法提現",
            ];

            return new JsonResponse($refundReturn);
        }

        $afterAmount = $redis->decrby("user:$userId", intval($amount));
        $beforeBalance = $user['balance'] + intval($beforeAmount);
        $afterBalance = $user['balance'] + $afterAmount;

        $refundInsert = new Refund();
        $refundInsert->setTradeNo($tradeNo)
            ->setUserId($user['id'])
            ->setUserName($user['nickName'])
            ->setAmount(intval($amount))
            ->setBeforeBalance($beforeBalance)
            ->setAfterBalance($afterBalance)
            ->setStatus(Refund::WAIT);
        $em->persist($refundInsert);
        $em->flush();
        $refundId = $refundInsert->getId();

        $refundArray = [
            'user_id' => $userId,
            'amount' => $amount,
            'order_id' => $refundId,
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
