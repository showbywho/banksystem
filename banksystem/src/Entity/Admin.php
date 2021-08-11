<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type = "integer")
     */
    private $id;

    /**
     * @ORM\Column(name = "account", type = "string", length = 180, unique = true)
     */
    private $account;

    /**
     * @ORM\Column(name = "roles", type = "json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(name = "password", type = "string")
     */
    private $password;

    /**
     * @ORM\Column(name = "balance", type = "float")
     */
    private $balance;

    /**
     * @ORM\Column(name = "total_refund", type = "float")
     */
    private $totalRefund;

    /**
     * @ORM\Column(name = "session_id", type = "string", length = 255, nullable = true)
     */
    private $sessionId;

    /**
     * @ORM\Column(name = "total_deposit", type = "float")
     */
    private $totalDeposit;

    /**
     * @ORM\Column(name = "create_time", type = "datetime")
     */
    private $createTime;

    /**
     * @ORM\Column(name = "update_time", type = "datetime", nullable = true)
     */
    private $updateTime;

    /**
     * @ORM\Column(name = "status", type = "integer")
     */
    private $status;

    /**
     * @ORM\Column(name = "nick_name", type = "string", length = 255)
     */
    private $nickName;

    /**
     * @ORM\Version @ORM\Column(name = "version", type="integer")
     */
    private $version;

    public function __construct()
    {
        $this->createTime = new \DateTime();
    }

    /**
     * 取得Admin表的id欄位
     *
     * @return self
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * 設定Admin表的id欄位
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
     * 取得Admin表的account欄位
     *
     * @return self
     */
    public function getAccount(): ?string
    {
        return $this->account;
    }

    /**
     * 設定Admin表的account欄位
     *
     * @param string $account
     * @return self
     */
    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * 取得Admin表的version欄位
     *
     * @return self
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * 設定Admin表的version欄位
     *
     * @param int $version
     * @return self
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * 取得Admin表的roles欄位
     *
     * @return self
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * 設定Admin表的roles欄位
     *
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * 取得Admin表的password欄位
     *
     * @return self
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * 設定Admin表的password欄位
     *
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    /**
     * 取得Admin表的balance欄位
     *
     * @return self
     */
    public function getBalance(): ?float
    {
        return $this->balance;
    }

    /**
     * 設定Admin表的balance欄位
     *
     * @param float $balance
     * @return self
     */
    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * 取得Admin表的total_refund欄位
     *
     * @return self
     */
    public function getTotalRefund(): ?float
    {
        return $this->totalRefund;
    }

    /**
     * 設定Admin表的total_refund欄位
     *
     * @param float $totalRefund
     * @return self
     */
    public function setTotalRefund(float $totalRefund): self
    {
        $this->totalRefund = $totalRefund;

        return $this;
    }

    /**
     * 取得Admin表的session_id欄位
     *
     * @return self
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * 設定Admin表的session_id欄位
     *
     * @param string $sessionId
     * @return self
     */
    public function setSessionId(?string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * 取得Admin表的total_deposit欄位
     *
     * @return self
     */
    public function getTotalDeposit(): ?float
    {
        return $this->totalDeposit;
    }

    /**
     * 設定Admin表的total_deposit欄位
     *
     * @param float $totalDeposit
     * @return self
     */
    public function setTotalDeposit(float $totalDeposit): self
    {
        $this->totalDeposit = $totalDeposit;

        return $this;
    }

    /**
     * 取得Admin表的create_time欄位
     *
     * @return self
     */
    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    /**
     * 設定Admin表的create_time欄位
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
     * 取得Admin表的update_time欄位
     *
     * @return self
     */
    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }

    /**
     * 設定Admin表的update_time欄位
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
     * 取得Admin表的status欄位
     *
     * @return self
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * 設定Admin表的status欄位
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
     * 取得Admin表的nick_name欄位
     *
     * @return self
     */
    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    /**
     * 設定Admin表的nick_name欄位
     *
     * @param string $nickName
     * @return self
     */
    public function setNickName(string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }
}
