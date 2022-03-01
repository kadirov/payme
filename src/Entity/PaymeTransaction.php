<?php

declare(strict_types=1);

namespace Kadirov\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Kadirov\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use Kadirov\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use Kadirov\Controller\PaymeInputAction;
use Kadirov\Controller\PaymeTransactionCreateAction;
use Kadirov\Repository\PaymeTransactionRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        'get' => [
            'security'              => "is_granted('ROLE_ADMIN')",
            'normalization_context' => ['groups' => ['paymeTransactions:read']],
        ],
    ],
    itemOperations: [
        'post' => [
            'method'      => 'post',
            'path'        => 'payments/payme',
            'deserialize' => false,
            'input'       => PaymeRequestDto::class,
            'controller'  => PaymeInputAction::class,
            'output'      => false,
        ],
        'make' => [
            'method'     => 'post',
            'controller' => PaymeTransactionCreateAction::class,
        ],
    ],
    denormalizationContext: ['groups' => ['paymeTransaction:write']],
    normalizationContext: ['groups' => ['paymeTransaction:read', 'paymeTransactions:read']],
)]
#[ORM\Entity(repositoryClass: PaymeTransactionRepository::class)]
class PaymeTransaction
{
    #[Groups(['paymeTransactions:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['paymeTransactions:read'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $paymeId;

    #[Groups(['paymeTransactions:read'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $time;

    #[Groups(['paymeTransactions:read'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $amount;

    #[Groups(['paymeTransactions:read'])]
    #[ORM\Column(type: 'bigint', nullable: true)]
    private $createTime;

    #[Groups(['paymeTransactions:read'])]
    #[ORM\Column(type: 'bigint', nullable: true)]
    private $performTime;

    #[Groups(['paymeTransactions:read'])]
    #[ORM\Column(type: 'bigint', nullable: true)]
    private $cancelTime;

    #[Groups(['paymeTransactions:read'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $reason;

    /**
     * A constant of {@see PaymeTransactionState}
     *
     * @see PaymeTransactionState
     */
    #[Groups(['paymeTransactions:read'])]
    #[ORM\Column(type: 'integer')]
    private $state = PaymeTransactionState::INITIAL;

    /**
     * Type of payment on your system.
     * for example: Plan, Buy products etc.
     */
    #[Groups(['paymeTransactions:read', 'paymeTransaction:write'])]
    #[ORM\Column(type: 'integer')]
    private $customType;

    /**
     * ID of payment on your system.
     * for example: Plan id, or id of buying products
     */
    #[Groups(['paymeTransactions:read', 'paymeTransaction:write'])]
    #[ORM\Column(type: 'integer')]
    private ?int $customId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymeId(): ?string
    {
        return $this->paymeId;
    }

    public function setPaymeId(?string $paymeId): self
    {
        $this->paymeId = $paymeId;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreateTime(): int
    {
        return (int)$this->createTime;
    }

    public function setCreateTime(?int $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getPerformTime(): int
    {
        return (int)$this->performTime;
    }

    public function setPerformTime(?int $performTime): self
    {
        $this->performTime = $performTime;

        return $this;
    }

    public function getCancelTime(): int
    {
        return (int)$this->cancelTime;
    }

    public function setCancelTime(?int $cancelTime): self
    {
        $this->cancelTime = $cancelTime;

        return $this;
    }

    /**
     * @return int|null A constant of {@see PaymeTransactionState}
     */
    public function getState(): ?int
    {
        return $this->state;
    }

    /**
     * @param int $state A constant of {@see PaymeTransactionState}
     *
     * @return $this
     */
    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getReason(): ?int
    {
        return $this->reason;
    }

    public function setReason(?int $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getCustomType(): ?int
    {
        return $this->customType;
    }

    public function setCustomType(?int $customType): self
    {
        $this->customType = $customType;

        return $this;
    }

    public function getCustomId(): ?int
    {
        return $this->customId;
    }

    public function setCustomId(?int $customId): self
    {
        $this->customId = $customId;

        return $this;
    }
}
