<?php

declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme\Interfaces;

use App\Entity\PaymeTransaction;

interface PaymeBeforeCancelFinishedPaymentInterface
{
    public function beforeCancelFinishedPayment(PaymeTransaction $transaction): void;
}
