<?php

declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme\Interfaces;

use App\Entity\PaymeTransaction;

interface PaymeAfterFinishPaymentInterface
{
    public function afterFinishPayment(PaymeTransaction $transaction): void;
}
