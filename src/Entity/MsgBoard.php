<?php

namespace App\Entity;

use App\Repository\MsgBoardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass = MsgBoardRepository::class)
 * @ORM\Table(name = "MsgBoard")
 */
class MsgBoard
{
    /**
     * @ORM\Id
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name = "contents", type = "string", length = 100, nullable = false, options = {"comment" = "留言內容"})
     */
    private $contents;

    /**
     * @ORM\Column(name = "owner", type = "string", length = 100, nullable = false, options = {"comment" = "留言者暱稱"})
     */
    private $owner;

    /**
     * @ORM\Column(name = "times", type = "string", length = 100, nullable = false, options = {"comment" = "留言時間"})
     */
    private $times;

    /**
     * @ORM\Column(name = "user_ip", type = "string", length = 100, nullable = false, options = {"comment" = "留言者IP"})
     */
    private $userIp;

    /**
     * @ORM\Column(name = "tag", type = "integer", nullable = true)
     */
    private $tag;

    /**
     * 取得MsgBoard表的id欄位
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 設定MsgBoard表的id欄位
     *
     * @param string $id  
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * 取得MsgBoard表的contents欄位
     *
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * 設定MsgBoard表的contents欄位
     *
     * @param string $contents  
     * @return self
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * 取得MsgBoard表的owner欄位
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * 設定MsgBoard表的owner欄位
     *
     * @param string $owner  
     * @return self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * 取得MsgBoard表的times欄位
     *
     * @return string
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * 設定MsgBoard表的times欄位
     *
     * @param string $times  
     * @return self
     */
    public function setTimes($times)
    {
        $this->times = $times;

        return $this;
    }

    /**
     * 取得MsgBoard表的user_ip欄位
     *
     * @return string
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * 設定MsgBoard表的user_ip欄位
     *
     * @param string $userIp  
     * @return self
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;

        return $this;
    }

    /**
     * 取得MsgBoard表的tag欄位
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * 設定MsgBoard表的tag欄位
     *
     * @param int $tag  
     * @return self
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * 取得MsgBoard表的所有欄位
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'contents' => $this->getContents(),
            'owner' => $this->getOwner(),
            'times' => $this->getTimes(),
            'userIp' => $this->getUserIp(),
            'tag' => $this->getTag(),
        ];
    }
}
