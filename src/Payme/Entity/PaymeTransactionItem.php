<?php

namespace Kadirov\Payme\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use Kadirov\Payme\Repository\PaymeTransactionItemRepository;

#[ORM\Entity(repositoryClass: PaymeTransactionItemRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            security: "is_granted('ROLE_ADMIN')"
        )
    ]
)]
class PaymeTransactionItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: PaymeTransaction::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private $transaction;

    #[ORM\Column(type: 'integer')]
    private $count;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'bigint')]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $code;

    #[ORM\Column(type: 'string', length: 255)]
    private $packageCode;

    #[ORM\Column(type: 'float')]
    private $vatPercent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransaction(): ?PaymeTransaction
    {
        return $this->transaction;
    }

    public function setTransaction(?PaymeTransaction $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPackageCode(): ?string
    {
        return $this->packageCode;
    }

    public function setPackageCode(string $packageCode): self
    {
        $this->packageCode = $packageCode;

        return $this;
    }

    public function getVatPercent(): ?float
    {
        return $this->vatPercent;
    }

    public function setVatPercent(float $vatPercent): self
    {
        $this->vatPercent = $vatPercent;

        return $this;
    }
}
