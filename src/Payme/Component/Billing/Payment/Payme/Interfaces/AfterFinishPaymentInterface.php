<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Interfaces;

use Kadirov\Payme\Entity\PaymeTransaction;

interface AfterFinishPaymentInterface
{
    public function afterFinishPayment(PaymeTransaction $transaction): void;
}
