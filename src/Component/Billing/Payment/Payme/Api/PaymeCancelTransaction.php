<?php

declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme\Api;

use Kadirov\Component\Billing\Payment\Payme\Api\Traits\TransactionTrait;
use Kadirov\Component\Billing\Payment\Payme\Constants\PaymeCancelingReason;
use Kadirov\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use Kadirov\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use Kadirov\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use Kadirov\Component\Billing\Payment\Payme\Interfaces\PaymeBeforeCancelFinishedPaymentInterface;
use Kadirov\Component\Billing\Payment\Payme\PaymeTransactionManager;
use Kadirov\Entity\PaymeTransaction;
use Kadirov\Repository\PaymeTransactionRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

class PaymeCancelTransaction
{
    use TransactionTrait;

    public function __construct(
        private PaymeTransactionRepository $transactionRepository,
        private PaymeTransactionManager $transactionManager,
        private PaymeBeforeCancelFinishedPaymentInterface $paymeBeforeCancelPayment,
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
    public function cancel(PaymeRequestDto $requestDto): PaymeTransaction
    {
        $transaction = $this->findTransactionByPaymeId($requestDto->getParams()->getId());

        switch ($transaction->getState()) {
            case PaymeTransactionState::INITIAL:
            case PaymeTransactionState::CREATED:
                $this->cancelCreatedTransaction($transaction, $requestDto);
                break;

            case PaymeTransactionState::FINISHED:
                $this->cancelFinishedTransaction($transaction, $requestDto);
                break;
        }

        return $transaction;
    }

    private function cancelCreatedTransaction(PaymeTransaction $transaction, PaymeRequestDto $requestDto): void
    {
        $transaction->setState(PaymeTransactionState::CANCELED);
        $this->updateTransaction($transaction, $requestDto);
    }

    /**
     * @param PaymeTransaction $transaction
     * @param PaymeRequestDto $requestDto
     * @throws Exception
     */
    private function cancelFinishedTransaction(PaymeTransaction $transaction, PaymeRequestDto $requestDto): void
    {
        $this->paymeBeforeCancelPayment->beforeCancelFinishedPayment($transaction);
        $transaction->setState(PaymeTransactionState::CANCELED_AFTER_FINISH);
        $this->updateTransaction($transaction, $requestDto);
    }

    private function updateTransaction(PaymeTransaction $transaction, PaymeRequestDto $requestDto): void
    {
        $transaction->setCancelTime(time());
        $transaction->setReason($requestDto->getParams()->getReason() ?? PaymeCancelingReason::UNKNOWN_ERROR);
        $this->transactionManager->save($transaction, true);
    }
}
