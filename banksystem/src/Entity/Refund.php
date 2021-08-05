<?php

namespace App\Entity;

use App\Repository\RefundRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name = "trade_no", type = "string", length = 255, options = {"comment" = "交易訂單號"})
     */
    private $tradeNo;

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
     * @ORM\Column(name = "before_balance", type = "float", options = {"comment" = "交易前餘額"})
     */
    private $beforeBalance;

    /**
     * @ORM\Column(name = "after_balance", type = "float", options = {"comment" = "交易後餘額"})
     */
    private $afterBalance;

    /**
     * @ORM\Column(name = "create_time", type = "datetime", options = {"comment" = "創建時間"})
     */
    private $createTime;

    /**
     * @ORM\Column(name = "update_time", type = "datetime", nullable = true, options = {"comment" = "訂單更新時間"})
     */
    private $updateTime;

    /**
     * @ORM\Column(name = "status", type = "integer", options = {"comment" = "訂單狀態"})
     */
    private $status;

    /**
     * @ORM\Column(name = "remark", type = "string", length = 255, nullable = true, options = {"comment" = "備註"})
     */
    private $remark;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTradeNo(): ?string
    {
        return $this->tradeNo;
    }

    public function setTradeNo(string $tradeNo): self
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBeforeBalance(): ?float
    {
        return $this->beforeBalance;
    }

    public function setBeforeBalance(float $beforeBalance): self
    {
        $this->beforeBalance = $beforeBalance;

        return $this;
    }

    public function getAfterBalance(): ?float
    {
        return $this->afterBalance;
    }

    public function setAfterBalance(float $afterBalance): self
    {
        $this->afterBalance = $afterBalance;

        return $this;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setCreateTime(\DateTimeInterface $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }
}
