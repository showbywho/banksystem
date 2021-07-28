<?php
namespace App\Controller;

use App\Entity\MsgBoard;
use App\Entity\Reply;
use App\Repository\MsgBoardRepository;
use App\Repository\ReplyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MsgBoardController extends AbstractController
{
    private $locationUrl = 'http://localhost:8000/';

    /**
     *
     * @Route("/")
     *
     * @return Response
     */
    public function index(Request $request, MsgBoardRepository $msgBoard)
    {
        $page = $request->query->getInt('p', 1);
        $pageQuery = $msgBoard->pageQuery($page);
        $pageConut = $msgBoard->pageConut($page);

        return $this->render('msgBoard/msg.html.twig', [
            'totalComments' => $pageConut['totalComments'],
            'totalPage' => $pageConut['totalPage'],
            'page' => $pageConut['page'],
            'comments' => $pageQuery
        ]);
    }

    /**
     * @Route("/getMsgReply/{pmId}")
     */
    public function getMsgReply($pmId, ReplyRepository $reply)
    {
        $users = $reply->msgReply($pmId);

        return new Response(
            json_encode($users)
        );
    }

    /**
     * @Route("/sendMsgReply/{pmId}")
     */
    public function sendMsgReply($pmId, Request $request, EntityManagerInterface $em)
    {
        $names = $request->request->get('names');
        $contents = $request->request->get('contents');
        $ip = $request->server->get('REMOTE_ADDR');

        if (!empty($request->server->get('HTTP_X_FORWARDED_FOR'))) {
            $ip = $request->server->get('HTTP_X_FORWARDED_FOR');
        }

        if (!empty($request->server->get('HTTP_CLIENT_IP'))) {
            $ip = $request->server->get('HTTP_CLIENT_IP');
        }

        $times = date('Y-m-d H:i:s');
        $replyInsert = new Reply();
        $replyInsert->setContents($contents);
        $replyInsert->setOwner($names);
        $replyInsert->setTimes($times);
        $replyInsert->setUserIp($ip);
        $replyInsert->setTag($pmId);
        $em->persist($replyInsert);
        $em->flush();

        return new RedirectResponse($this->locationUrl);
    }

    /**
     * @Route("/newMsgReply")
     */
    public function newMsgReply(Request $request, EntityManagerInterface $em)
    {
        $names = $request->request->get('names');
        $contents = $request->request->get('contents');
        $ip = $request->server->get('REMOTE_ADDR');

        if (!empty($request->server->get('HTTP_X_FORWARDED_FOR'))) {
            $ip = $request->server->get('HTTP_X_FORWARDED_FOR');
        }

        if (!empty($request->server->get('HTTP_CLIENT_IP'))) {
            $ip = $request->server->get('HTTP_CLIENT_IP');
        }

        $times = date('Y-m-d H:i:s');
        $msgInsert = new MsgBoard();
        $msgInsert->setContents($contents);
        $msgInsert->setOwner($names);
        $msgInsert->setTimes($times);
        $msgInsert->setUserIp($ip);
        $em->persist($msgInsert);
        $em->flush();

        return new RedirectResponse($this->locationUrl);
    }

    /**
     * @Route("/updateMsgReply/{id}")
     */
    public function updateMsgReply($id, Request $request, EntityManagerInterface $em)
    {
        $contents = $request->request->get('contents');
        $ip = $request->server->get('REMOTE_ADDR');

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

        return new RedirectResponse($this->locationUrl);
    }

    /**
     * @Route("/delMsgReply")
     */
    public function delMsgReply(Request $request, EntityManagerInterface $em)
    {
        $id = $request->request->get('listId');
        $msgBoard = $em->getRepository('App\Entity\MsgBoard')->find($id);

        if ($msgBoard) {
            $em->remove($msgBoard);
            $replyBoard = $em->getRepository('App\Entity\Reply')->findBy(['tag' => $id]);

            foreach ($replyBoard as $value) {
                $em->remove($value);
            }
        }

        $em->flush();

        return new RedirectResponse($this->locationUrl);
    }
}
