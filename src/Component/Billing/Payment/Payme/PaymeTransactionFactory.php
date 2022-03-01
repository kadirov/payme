<?php

declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme;

use Kadirov\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use Kadirov\Entity\PaymeTransaction;

class PaymeTransactionFactory
{
    public function create(int $customerType, int $customerId): PaymeTransaction
    {
        return (new PaymeTransaction())
            ->setCreateTime(time())
            ->setCustomType($customerType)
            ->setCustomId($customerId)
            ->setState(PaymeTransactionState::INITIAL);
    }
}
