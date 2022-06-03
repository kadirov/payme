<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Interfaces;

use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\BeforeCancelFinishedPaymentException;
use Kadirov\Payme\Entity\PaymeTransaction;

interface BeforeCancelFinishedPaymentInterface
{
    /**
     * @param PaymeTransaction $transaction
     * @return void
     * @throws BeforeCancelFinishedPaymentException
     */
    public function beforeCancelFinishedPayment(PaymeTransaction $transaction): void;
}
