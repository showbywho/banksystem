<?php

namespace App\Controller;

use App\Repository\IncomingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param object IncomingRepository $incoming
     *
     * @return JsonResponse
     */
    public function insertIncoming(Request $request, IncomingRepository $incoming)
    {
        $id = $request->getSession()->get('id');
        $amount = $request->request->get('amount');

        if (!$id) {
            $incomingReturn = ['ret' => 'error', 'msg' => "無權限執行此操作"];

            return new JsonResponse($incomingReturn);
        }

        if (0 >= $amount) {
            $incomingReturn = ['ret' => 'error', 'msg' => "存款金額($amount)不得為負數或0"];

            return new JsonResponse($incomingReturn);
        }

        $balanceReturn = $incoming->doIncoming($amount, $id, $request);
        $incomingReturn = ['ret' => 'ok', 'msg' => "存款成功", 'result' => ['balance' => $balanceReturn]];

        return new JsonResponse($incomingReturn);
    }
}
