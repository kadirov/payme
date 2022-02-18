<?php

declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme\Api\Traits;

use App\Component\Billing\Payment\Payme\Exceptions\Constants\PaymeExceptionText;
use App\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use App\Entity\PaymeTransaction;
use App\Repository\PaymeTransactionRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Trait GetTransaction
 *
 * @package App\Components\Billing\Payment\Payme\Api\Traits
 */
trait TransactionTrait
{
    use GetAccountTrait;

    private PaymeTransactionRepository $transactionRepository;

    /**
     * @param int $transactionId
     * @return PaymeTransaction
     * @throws PaymeException
     */
    private function findTransactionOrError(int $transactionId): PaymeTransaction
    {
        $model = $this->getTransactionRepository()->find($transactionId);

        if ($model === null) {
            throw new PaymeException(PaymeExceptionText::TRANSACTION_IS_NOT_FOUND_EN, PaymeException::WRONG_USER_DATA);
        }

        return $model;
    }

    /**
     * @param string|null $paymeId
     * @return PaymeTransaction
     * @throws PaymeException
     * @throws NonUniqueResultException
     */
    private function findTransactionByPaymeId(?string $paymeId): PaymeTransaction
    {
        if ($paymeId === null) {
            throw new PaymeException(
                PaymeExceptionText::ID_IS_NOT_FOUND_EN,
                PaymeException::MISSING_REQUIRED_FIELDS
            );
        }

        $model = $this->getTransactionRepository()->findOneByPaymeId($paymeId);

        if ($model === null) {
            throw new PaymeException(
                PaymeExceptionText::TRANSACTION_IS_NOT_FOUND_EN,
                PaymeException::TRANSACTION_IS_NOT_FOUND
            );
        }

        return $model;
    }

    private function getTransactionRepository(): PaymeTransactionRepository
    {
        return $this->transactionRepository;
    }

    private function setTransactionRepository(PaymeTransactionRepository $repository): void
    {
        $this->transactionRepository = $repository;
    }
}
