<?php

declare(strict_types=1);

namespace Kadirov\Payme\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use Kadirov\Payme\Controller\PaymeInputAction;
use Kadirov\Payme\Repository\PaymeTransactionRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: 'payments/payme',
            controller: PaymeInputAction::class,
            input: PaymeRequestDto::class,
            output: false,
            deserialize: false
        ),
        new Get(
            normalizationContext: ['groups' => ['paymeTransactions:read']],
            security: "is_granted('ROLE_ADMIN')"
        )
    ],
    normalizationContext: ['groups' => ['paymeTransaction:read', 'paymeTransactions:read']],
    denormalizationContext: ['groups' => ['paymeTransaction:write']],
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

    #[ORM\Column(type: 'bigint', nullable: true)]
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

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: PaymeTransactionItem::class)]
    #[Groups(['paymeTransactions:read'])]
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): self
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

    /**
     * @return Collection<int, PaymeTransactionItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(PaymeTransactionItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setTransaction($this);
        }

        return $this;
    }

    public function removeItem(PaymeTransactionItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getTransaction() === $this) {
                $item->setTransaction(null);
            }
        }

        return $this;
    }
}
