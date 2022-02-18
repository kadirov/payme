<?php

declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme;

use App\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use App\Entity\PaymeTransaction;

class PaymeTransactionFactory
{
    public function create(): PaymeTransaction
    {
        return (new PaymeTransaction())
            ->setCreateTime(time())
            ->setState(PaymeTransactionState::INITIAL);
    }
}
