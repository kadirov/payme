<?php

declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme\Api;

use App\Component\Billing\Payment\Payme\Api\Traits\IsTimeoutTrait;
use App\Component\Billing\Payment\Payme\Api\Traits\MarkTransactionAsTimeoutTrait;
use App\Component\Billing\Payment\Payme\Api\Traits\TransactionTrait;
use App\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use App\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use App\Component\Billing\Payment\Payme\Exceptions\Constants\PaymeExceptionText;
use App\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use App\Component\Billing\Payment\Payme\Interfaces\PaymeAfterFinishPaymentInterface;
use App\Component\Billing\Payment\Payme\PaymeTransactionManager;
use App\Entity\PaymeTransaction;
use App\Repository\PaymeTransactionRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

class PaymePerformTransaction
{
    use TransactionTrait;
    use IsTimeoutTrait;
    use MarkTransactionAsTimeoutTrait;

    public function __construct
    (
        PaymeTransactionRepository $transactionRepository,
        private PaymeTransactionManager $transactionManager,
        private PaymeAfterFinishPaymentInterface $afterFinishPayment
    ) {
        $this->setTransactionRepository($transactionRepository);
    }

    /**
     * @param PaymeRequestDto $requestDto
     * @return PaymeTransaction
     * @throws PaymeException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function check(PaymeRequestDto $requestDto): PaymeTransaction
    {
        $transaction = $this->findTransactionByPaymeId($requestDto->getParams()->getId());

        if ($transaction->getState() === PaymeTransactionState::FINISHED) {
            // everything ok, had already finished
            return $transaction;
        }

        if ($transaction->getState() !== PaymeTransactionState::CREATED) {
            throw new PaymeException(PaymeExceptionText::WRONG_STATE_EN, PaymeException::CANNOT_PROCESS_OPERATION);
        }

        if ($this->isTimeout($transaction->getCreateTime())) {
            $this->markTransactionAsTimeout($transaction, $this->transactionManager);
            throw new PaymeException(
                PaymeExceptionText::TRANSACTION_TIMEOUT_EN,
                PaymeException::CANNOT_PROCESS_OPERATION
            );
        }

        $transaction->setPerformTime(time());
        $transaction->setState(PaymeTransactionState::FINISHED);
        $this->transactionManager->save($transaction, true);
        $this->afterFinishPayment->afterFinishPayment($transaction);

        return $transaction;
    }
}
