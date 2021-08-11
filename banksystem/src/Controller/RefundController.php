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
     * @param object EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function insertRefund(Request $request, AdminRepository $admin, EntityManagerInterface $em)
    {
        $userId = $request->getSession()->get('id');
        $amount = $request->request->get('amount');

        if (!$userId) {
            $refundReturn = ['ret' => 'error', 'msg' => "無權限執行此操作"];

            return new JsonResponse($refundReturn);
        }

        if (0 >= $amount) {
            $refundReturn = ['ret' => 'error', 'msg' => "存款金額($amount)不得為負數或0"];

            return new JsonResponse($refundReturn);
        }

        $balanceReturn = $admin->updateBalancePessimistic($amount, $userId, $request);

        if (isset($balanceReturn['error'])) {
            $balance = $balanceReturn['error'];
            $refundReturn = ['ret' => 'ok', 'msg' => "餘額($balance)不足無法提現"];

            return new JsonResponse($refundReturn);
        }

        $msg = "提現成功";

        if (!$balanceReturn['status'] == '2') {
            $msg = "更新操作逾期,請重新操作一次！";
        }

        $refundInsert = new Refund();
        $refundInsert->setTradeNo('tradeNO' . strtotime("now") . rand(100, 999))
            ->setUserId($userId)
            ->setUserName($balanceReturn['nickName'])
            ->setAmount(intval($amount))
            ->setBeforeBalance($balanceReturn['balance'])
            ->setAfterBalance($balanceReturn['countAmount'])
            ->setStatus($balanceReturn['status']);
        $em->persist($refundInsert);
        $em->flush();
        $request->getSession()->set('balance', $balanceReturn['countAmount']);
        $refundReturn = ['ret' => 'ok', 'msg' => $msg, 'result' => ['balance' => $balanceReturn['countAmount']]];

        return new JsonResponse($refundReturn);
    }
}
