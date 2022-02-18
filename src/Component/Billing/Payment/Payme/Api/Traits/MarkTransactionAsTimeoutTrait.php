<?php

declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme\Api\Traits;

use App\Component\Billing\Payment\Payme\Constants\PaymeCancelingReason;
use App\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use App\Component\Billing\Payment\Payme\PaymeTransactionManager;
use App\Entity\PaymeTransaction;

trait MarkTransactionAsTimeoutTrait
{
    private function markTransactionAsTimeout(
        PaymeTransaction $transaction,
        PaymeTransactionManager $transactionManager
    ): void {
        $transaction->setState(PaymeTransactionState::CANCELED);
        $transaction->setCancelTime(time());
        $transaction->setReason(PaymeCancelingReason::TIMEOUT);
        $transactionManager->save($transaction, true);
    }
}
