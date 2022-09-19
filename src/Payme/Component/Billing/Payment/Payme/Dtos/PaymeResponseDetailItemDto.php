<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Dtos;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PaymeResponseDetailItemDto
{
    public function __construct(
        private int $count,
        private string $title,
        private int $price,
        private string $code,

        #[SerializedName('package_code')]
        private string $packageCode,

        #[SerializedName('vat_percent')]
        private float $vatPercent,
    ) {
    }

    public function getPackageCode(): string
    {
        return $this->packageCode;
    }

    public function getVatPercent(): float
    {
        return $this->vatPercent;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
