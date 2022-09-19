<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme;

use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use Kadirov\Payme\Entity\PaymeTransaction;

class PaymeTransactionFactory
{
    public function create(string $amount): PaymeTransaction
    {
        return (new PaymeTransaction())
            ->setCreateTime(time())
            ->setAmount($amount)
            ->setState(PaymeTransactionState::INITIAL);
    }
}
