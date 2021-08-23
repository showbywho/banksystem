<?php

namespace App\Controller;

use App\Entity\Incoming;
use App\Repository\IncomingRepository;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IncomingController extends AbstractController
{
    private $locationUrl = 'http://localhost:8000/login';

    /**
     * @Route("/incoming", methods = {"GET"})
     *
     * @param  Request $request
     * @param  IncomingRepository $incoming
     *
     * @return RedirectResponse|Response
     */
    public function index(Request $request, IncomingRepository $incoming)
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            return new RedirectResponse($this->locationUrl);
        }

        $request = $request->getSession();
        $userIncomingListData = $incoming->getUserIncomingList($id);

        return $this->render('incoming/index.html.twig', [
            'user_incoming_list_data' => $userIncomingListData,
            'uid' =>  $request->get('id'),
            'nick_name' => $request->get('nick_name'),
            'balance' => $request->get('balance'),
        ]);
    }

    /**
     * @Route("/redisIncoming", methods = {"POST"})
     *
     * @param Request $request
     * @param AdminRepository $admin
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function redisIncoming(
        Request $request,
        AdminRepository $admin,
        EntityManagerInterface $em
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

        $tradeNo = 'tradeNO' . strtotime('now') . rand(100, 999);
        $redis = new \Predis\Client();
        $beforeAmount = $redis->get("user:$userId");
        $afterAmount = $redis->incrby("user:$userId", intval($amount));
        $beforeBalance = $user['balance'] + intval($beforeAmount);
        $afterBalance = $user['balance'] + $afterAmount;

        $incomingInsert = new Incoming();
        $incomingInsert->setTradeNo($tradeNo)
            ->setUserId($user['id'])
            ->setUserName($user['nickName'])
            ->setAmount(intval($amount))
            ->setBeforeBalance($beforeBalance)
            ->setAfterBalance($afterBalance)
            ->setStatus(Incoming::WAIT);
        $em->persist($incomingInsert);
        $em->flush();

        $incomingId = $incomingInsert->getId();
        $incomingArray = [
            'user_id' => $userId,
            'amount' => $amount,
            'order_id' => $incomingId,
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
