<?php

declare(strict_types=1);

namespace Kadirov\Controller;

use Kadirov\Component\Billing\Payment\Payme\PaymeTransactionFactory;
use Kadirov\Component\Billing\Payment\Payme\PaymeTransactionManager;
use Kadirov\Controller\Base\AbstractController;
use Kadirov\Entity\PaymeTransaction;

class PaymeTransactionCreateAction extends AbstractController
{
    public function __invoke(PaymeTransactionFactory $factory, PaymeTransactionManager $manager): PaymeTransaction
    {
        $transaction = $factory->create();
        $manager->save($transaction, true);
        return $transaction;
    }
}
