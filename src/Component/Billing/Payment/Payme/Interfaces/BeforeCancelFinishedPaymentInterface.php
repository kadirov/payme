<?php

declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme\Interfaces;

use Kadirov\Component\Billing\Payment\Payme\Exceptions\BeforeCancelFinishedPaymentException;
use Kadirov\Entity\PaymeTransaction;

interface BeforeCancelFinishedPaymentInterface
{
    /**
     * @param PaymeTransaction $transaction
     * @return void
     * @throws BeforeCancelFinishedPaymentException
     */
    public function beforeCancelFinishedPayment(PaymeTransaction $transaction): void;
}
