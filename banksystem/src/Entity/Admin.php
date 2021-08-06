<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin implements UserInterface
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->account;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function __toString(): string
    {
        return $this->account;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getTotalRefund(): ?float
    {
        return $this->totalRefund;
    }

    public function setTotalRefund(float $totalRefund): self
    {
        $this->totalRefund = $totalRefund;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(?string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getTotalDeposit(): ?float
    {
        return $this->totalDeposit;
    }

    public function setTotalDeposit(float $totalDeposit): self
    {
        $this->totalDeposit = $totalDeposit;

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

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }
}
