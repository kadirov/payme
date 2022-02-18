<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\Billing\Payment\Payme\PaymeTransactionFactory;
use App\Component\Billing\Payment\Payme\PaymeTransactionManager;
use App\Controller\Base\AbstractController;
use App\Entity\PaymeTransaction;

class PaymeTransactionCreateAction extends AbstractController
{
    public function __invoke(PaymeTransactionFactory $factory, PaymeTransactionManager $manager): PaymeTransaction
    {
        $transaction = $factory->create();
        $manager->save($transaction, true);
        return $transaction;
    }
}
