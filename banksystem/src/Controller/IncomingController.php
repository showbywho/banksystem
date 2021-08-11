<?php

namespace App\Controller;

use App\Entity\Incoming;
use App\Repository\IncomingRepository;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IncomingController extends AbstractController
{
    private $locationUrl = 'http://localhost:8000/login';

    /**
     * @Route("/incoming", methods = {"GET"})
     *
     * @param object Request $request
     * @param object IncomingRepository $incoming
     *
     * @return array
     */
    public function index(Request $request, IncomingRepository $incoming): Response
    {
        $id = $request->getSession()->get('id');

        if (!$id) {
            return new RedirectResponse($this->locationUrl);
        }

        $request = $request->getSession();
        $listArray = $incoming->getList($id);

        return $this->render('incoming/index.html.twig', [
            'listArray' => $listArray,
            'uid' =>  $request->get('id'),
            'nickName' => $request->get('nickName'),
            'balance' => $request->get('balance'),
        ]);
    }

    /**
     * @Route("/incoming", methods = {"POST"})
     *
     * @param object Request $request
     * @param object IncomingRepository $admin
     * @param object EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    public function insertIncoming(Request $request, AdminRepository $admin, EntityManagerInterface $em)
    {
        $userId = $request->getSession()->get('id');
        $amount = $request->request->get('amount');

        if (!$userId) {
            $incomingReturn = ['ret' => 'error', 'msg' => "無權限執行此操作"];

            return new JsonResponse($incomingReturn);
        }

        if (0 >= $amount) {
            $incomingReturn = ['ret' => 'error', 'msg' => "存款金額($amount)不得為負數或0"];

            return new JsonResponse($incomingReturn);
        }

        $balanceReturn = $admin->updateBalanceOptimistic($amount, $userId);
        $msg = "存款成功";

        if ($balanceReturn['status'] == '2') {
            $msg = "更新操作逾期,請重新操作一次！";
        }

        $incomingInsert = new Incoming();
        $incomingInsert->setTradeNo('tradeNO' . strtotime("now") . rand(100, 999))
            ->setUserId($userId)
            ->setUserName($balanceReturn['nickName'])
            ->setAmount(intval($amount))
            ->setBeforeBalance($balanceReturn['balance'])
            ->setAfterBalance($balanceReturn['countAmount'])
            ->setStatus($balanceReturn['status']);
        $em->persist($incomingInsert);
        $em->flush();
        $request->getSession()->set('balance', $balanceReturn['countAmount']);
        $incomingReturn = ['ret' => 'ok', 'msg' => $msg, 'result' => ['balance' => $balanceReturn['countAmount']]];

        return new JsonResponse($incomingReturn);
    }
}
