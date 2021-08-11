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

    public function __construct()
    {
        $this->createTime = new \DateTime();
    }

    /**
     * 取得Refund表的id欄位
     *
     * @return self
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
     * 取得Refund表的trade_no欄位
     *
     * @return self
     */
    public function getTradeNo(): ?string
    {
        return $this->tradeNo;
    }

    /**
     * 設定Refund表的trade_no欄位
     *
     * @param string $tradeNo
     * @return self
     */
    public function setTradeNo(string $tradeNo): self
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }

    /**
     * 取得Refund表的user_id欄位
     *
     * @return self
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
     * @return self
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
     * @return self
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
     * 取得Refund表的before_balance欄位
     *
     * @return self
     */
    public function getBeforeBalance(): ?float
    {
        return $this->beforeBalance;
    }

    /**
     * 設定Refund表的before_balance欄位
     *
     * @param float $beforeBalance
     * @return self
     */
    public function setBeforeBalance(float $beforeBalance): self
    {
        $this->beforeBalance = $beforeBalance;

        return $this;
    }

    /**
     * 取得Refund表的after_balance欄位
     *
     * @return self
     */
    public function getAfterBalance(): ?float
    {
        return $this->afterBalance;
    }

    /**
     * 設定Refund表的after_balance欄位
     *
     * @param float $afterBalance
     * @return self
     */
    public function setAfterBalance(float $afterBalance): self
    {
        $this->afterBalance = $afterBalance;

        return $this;
    }

    /**
     * 取得Refund表的create_time欄位
     *
     * @return self
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

    /**
     * 取得Refund表的update_time欄位
     *
     * @return self
     */
    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }

    /**
     * 設定Refund表的update_time欄位
     *
     * @param \DateTimeInterface $updateTime
     * @return self
     */
    public function setUpdateTime(?\DateTimeInterface $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * 取得Refund表的status欄位
     *
     * @return self
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * 設定Refund表的status欄位
     *
     * @param int $status
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * 取得Refund表的remark欄位
     *
     * @return self
     */
    public function getRemark(): ?string
    {
        return $this->remark;
    }

    /**
     * 設定Refund表的remark欄位
     *
     * @param string $remark
     * @return self
     */
    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }
}
