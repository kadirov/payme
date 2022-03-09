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
        'post' => [
            'method'      => 'post',
            'path'        => 'payments/payme',
            'deserialize' => false,
            'input'       => PaymeRequestDto::class,
            'controller'  => PaymeInputAction::class,
            'output'      => false,
        ],
    ],
    itemOperations: [
        'get' => [
            'security'              => "is_granted('ROLE_ADMIN')",
            'normalization_context' => ['groups' => ['paymeTransactions:read']],
        ],
    ],
    denormalizationContext: ['groups' => ['paymeTransaction:write']],
    normalizationContext: ['groups' => ['paymeTransaction:read', 'paymeTransactions:read']],
)]
#[ORM\Entity(repositoryClass: PaymeTransactionRepository::class)]
class PaymeTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['paymeTransactions:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['paymeTransactions:read'])]
    private $paymeId;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['paymeTransactions:read'])]
    private $time;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['paymeTransactions:read'])]
    private $amount;

    #[ORM\Column(type: 'bigint', nullable: true)]
    #[Groups(['paymeTransactions:read'])]
    private $createTime;

    #[ORM\Column(type: 'bigint', nullable: true)]
    #[Groups(['paymeTransactions:read'])]
    private $performTime;

    #[ORM\Column(type: 'bigint', nullable: true)]
    #[Groups(['paymeTransactions:read'])]
    private $cancelTime;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['paymeTransactions:read'])]
    private $reason;

    /**
     * A constant of {@see PaymeTransactionState}
     *
     * @see PaymeTransactionState
     */
    #[ORM\Column(type: 'integer')]
    #[Groups(['paymeTransactions:read'])]
    private $state = PaymeTransactionState::INITIAL;

    /**
     * Type of payment on your system.
     * for example: Plan, Buy products etc.
     */
    #[ORM\Column(type: 'integer')]
    #[Groups(['paymeTransactions:read', 'paymeTransaction:write'])]
    private $customType;

    /**
     * ID of payment on your system.
     * for example: Plan id, or id of buying products
     */
    #[ORM\Column(type: 'integer')]
    #[Groups(['paymeTransactions:read', 'paymeTransaction:write'])]
    private $customId;

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
