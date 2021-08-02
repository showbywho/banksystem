<?php

namespace App\Controller;

use App\Entity\MsgBoard;
use App\Entity\Reply;
use App\Repository\MsgBoardRepository;
use App\Repository\ReplyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MsgBoardController extends AbstractController
{
    /**
     *
     * @Route("/", methods = {"GET"})
     *
     * @return Response
     */
    public function index(Request $request, MsgBoardRepository $msgBoard)
    {
        $page = $request->query->getInt('p', 1);
        $pageQuery = $msgBoard->pageQuery($page);
        $pageCount = $msgBoard->pageCount($page);

        return $this->render('msgBoard/msg.html.twig', [
            'totalComments' => $pageCount['totalComments'],
            'totalPage'     => $pageCount['totalPage'],
            'page'          => $pageCount['page'],
            'comments'      => $pageQuery,
        ]);
    }

    /**
     * @Route("/{pmId}", methods = {"GET"})
     */
    public function getMsgReply($pmId, ReplyRepository $reply)
    {
        $users = $reply->msgReply($pmId);

        return new Response(
            json_encode(['status' => 200, 'msg' => "success", 'data' => $users])
        );
    }

    /**
     * @Route("/{pmId}", methods = {"POST"})
     */
    public function sendMsgReply($pmId, Request $request, EntityManagerInterface $em)
    {
        $contents = $request->request->get('replyContents');
        $names    = $request->request->get('replyName');
        $ip       = $request->server->get('REMOTE_ADDR');

        if (!empty($request->server->get('HTTP_X_FORWARDED_FOR'))) {
            $ip = $request->server->get('HTTP_X_FORWARDED_FOR');
        }

        if (!empty($request->server->get('HTTP_CLIENT_IP'))) {
            $ip = $request->server->get('HTTP_CLIENT_IP');
        }

        $times       = date('Y-m-d H:i:s');
        $replyInsert = new Reply();
        $replyInsert->setContents($contents);
        $replyInsert->setOwner($names);
        $replyInsert->setTimes($times);
        $replyInsert->setUserIp($ip);
        $replyInsert->setTag($pmId);
        $em->persist($replyInsert);
        $em->flush();

        return new Response(
            json_encode(['status' => 200, 'msg' => '回覆留言成功！'])
        );
    }

    /**
     * @Route("/", methods = {"POST"})
     */
    public function newMsgReply(Request $request, EntityManagerInterface $em)
    {
        $ip       = $request->server->get('REMOTE_ADDR');
        $contents = $request->request->get('newContents');
        $names    = $request->request->get('newName');

        if (!empty($request->server->get('HTTP_X_FORWARDED_FOR'))) {
            $ip = $request->server->get('HTTP_X_FORWARDED_FOR');
        }

        if (!empty($request->server->get('HTTP_CLIENT_IP'))) {
            $ip = $request->server->get('HTTP_CLIENT_IP');
        }

        $times     = date('Y-m-d H:i:s');
        $msgInsert = new MsgBoard();
        $msgInsert->setContents($contents);
        $msgInsert->setOwner($names);
        $msgInsert->setTimes($times);
        $msgInsert->setUserIp($ip);
        $em->persist($msgInsert);
        $em->flush();

        return new Response(
            json_encode(['status' => 200, 'msg' => '新增留言成功！'])
        );
    }

    /**
     * @Route("/{id}", methods = {"PUT"})
     */
    public function updateMsgReply($id, Request $request, EntityManagerInterface $em)
    {
        $contents = $request->request->get('updateContents');
        $ip       = $request->server->get('REMOTE_ADDR');

        if (!empty($request->server->get('HTTP_X_FORWARDED_FOR'))) {
            $ip = $request->server->get('HTTP_X_FORWARDED_FOR');
        }

        if (!empty($request->server->get('HTTP_CLIENT_IP'))) {
            $ip = $request->server->get('HTTP_CLIENT_IP');
        }

        $msgBoard = $em->find('App\Entity\MsgBoard', $id);
        $msgBoard->setContents($contents);
        $msgBoard->setUserIp($ip);
        $em->persist($msgBoard);
        $em->flush();

        return new Response(
            json_encode(['status' => 200, 'msg' => '修改留言成功！'])
        );
    }

    /**
     * @Route("/{id}", methods = {"DELETE"})
     */
    public function delMsgReply($id, EntityManagerInterface $em)
    {
        $msgBoard = $em->getRepository('App\Entity\MsgBoard')->find($id);

        if ($msgBoard) {
            $em->remove($msgBoard);
            $replyBoard = $em->getRepository('App\Entity\Reply')->findBy(['tag' => $id]);

            foreach ($replyBoard as $value) {
                $em->remove($value);
            }
        }

        $em->flush();

        return new Response(
            json_encode(['status' => 200, 'msg' => '刪除留言成功'])
        );
    }
}
