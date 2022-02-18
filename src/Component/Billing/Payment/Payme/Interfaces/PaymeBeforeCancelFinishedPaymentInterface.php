<?php

declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme\Interfaces;

use Kadirov\Entity\PaymeTransaction;

interface PaymeBeforeCancelFinishedPaymentInterface
{
    public function beforeCancelFinishedPayment(PaymeTransaction $transaction): void;
}
