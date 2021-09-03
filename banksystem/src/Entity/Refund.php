<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RefundRepository;

/**
 * @ORM\Entity(repositoryClass=RefundRepository::class)
 */
class Refund
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type = "integer")
     */
    private $id;

    /**
     * @ORM\Column(name = "user_id", type = "integer", options = {"comment" = "創建者ID"})
     */
    private $userId;

    /**
     * @ORM\Column(name = "user_name", type = "string", length = 255, options = {"comment" = "創建者暱稱"})
     */
    private $userName;

    /**
     * @ORM\Column(name = "amount", type = "float", options = {"comment" = "交易金額"})
     */
    private $amount;

    /**
     * @ORM\Column(name = "create_time", type = "datetime", options = {"comment" = "創建時間"})
     */
    private $createTime;

    public function __construct()
    {
        $this->createTime = new \DateTime();
    }

    /**
     * 取得Refund表的id欄位
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * 設定Refund表的id欄位
     *
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * 取得Refund表的user_id欄位
     *
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * 設定Refund表的user_id欄位
     *
     * @param int $userId
     * @return self
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * 取得Refund表的user_name欄位
     *
     * @return string
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * 設定Refund表的user_name欄位
     *
     * @param string $userName
     * @return self
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * 取得Refund表的amount欄位
     *
     * @return float
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * 設定Refund表的amount欄位
     *
     * @param float $amount
     * @return self
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * 取得Refund表的create_time欄位
     *
     * @return \DateTimeInterface
     */
    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    /**
     * 設定Refund表的create_time欄位
     *
     * @param \DateTimeInterface $createTime
     * @return self
     */
    public function setCreateTime(\DateTimeInterface $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }
}
