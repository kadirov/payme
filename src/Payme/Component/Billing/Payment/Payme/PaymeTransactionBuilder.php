<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme;

use Doctrine\ORM\EntityManagerInterface;
use Kadirov\Payme\Entity\PaymeTransaction;
use LogicException;

class PaymeTransactionBuilder
{
    private ?PaymeTransaction $transaction;

    public function __construct(
        private PaymeTransactionFactory $transactionFactory,
        private PaymeTransactionItemFactory $transactionItemFactory,
        private PaymeTransactionManager $transactionManager,
        private PaymeTransactionItemManager $transactionItemManager,
        private EntityManagerInterface $entityManager,
    ) {
        $this->transaction = null;
    }

    public function createTransaction(string $amount): void
    {
        $this->transaction = $this->transactionFactory->create($amount);
        $this->transactionManager->save($this->transaction);
    }

    public function addItem(
        string $code,
        int $count,
        string $packageCode,
        string $price,
        string $title,
        float $vatPercent
    ): void {
        $this->throwErrorIfTransactionIsNotCreated();

        $item = $this->transactionItemFactory->create(
            $this->transaction,
            $code,
            $count,
            $packageCode,
            $price,
            $title,
            $vatPercent
        );

        $this->transactionItemManager->save($item);
    }

    public function getResult(): PaymeTransaction
    {
        $this->throwErrorIfTransactionIsNotCreated();
        $this->entityManager->flush();

        return $this->transaction;
    }

    private function throwErrorIfTransactionIsNotCreated(): void
    {
        if ($this->transaction === null) {
            throw new LogicException('You should call create() method first');
        }
    }
}
