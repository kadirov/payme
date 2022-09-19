<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme;

use Kadirov\Payme\Entity\PaymeTransaction;
use Kadirov\Payme\Entity\PaymeTransactionItem;

class PaymeTransactionItemFactory
{
    public function create(
        PaymeTransaction $transaction,
        string $code,
        int $count,
        string $packageCode,
        string $price,
        string $title,
        float $vatPercent
    ): PaymeTransactionItem {
        return (new PaymeTransactionItem())
            ->setCode($code)
            ->setCount($count)
            ->setPackageCode($packageCode)
            ->setPrice($price)
            ->setTitle($title)
            ->setTransaction($transaction)
            ->setVatPercent($vatPercent);
    }
}
