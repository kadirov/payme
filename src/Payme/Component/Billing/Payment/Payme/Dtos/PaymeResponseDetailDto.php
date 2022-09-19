<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Dtos;

class PaymeResponseDetailDto
{
    /**
     * @var PaymeResponseDetailItemDto[]
     */
    private array $items;

    /**
     * @param PaymeResponseDetailItemDto[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @return PaymeResponseDetailItemDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
