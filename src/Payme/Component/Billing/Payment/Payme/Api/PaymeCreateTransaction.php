<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Api;

use Kadirov\Payme\Component\Billing\Payment\Payme\Api\Traits\GetAccountTrait;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\Traits\IsTimeoutTrait;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\Traits\MarkTransactionAsTimeoutTrait;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\Traits\TransactionTrait;
use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\Constants\PaymeExceptionText;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use Kadirov\Payme\Component\Billing\Payment\Payme\PaymeTransactionManager;
use Kadirov\Payme\Entity\PaymeTransaction;
use Kadirov\Payme\Repository\PaymeTransactionRepository;
use Exception;

/**
 * Class PaymeCreateTransaction
 *
 * @package App\Components\Billing\Payment\Payme\Api
 */
class PaymeCreateTransaction
{
    use GetAccountTrait;
    use TransactionTrait;
    use IsTimeoutTrait;
    use MarkTransactionAsTimeoutTrait;

    private PaymeTransactionManager $transactionManager;

    public function __construct(
        PaymeTransactionRepository $paymeTransactionRepository,
        PaymeTransactionManager $transactionManager
    ) {
        $this->setTransactionRepository($paymeTransactionRepository);
        $this->transactionManager = $transactionManager;
    }

    /**
     * @param PaymeRequestDto $requestDto
     * @return PaymeTransaction
     * @throws PaymeException
     * @throws Exception
     */
    public function check(PaymeRequestDto $requestDto): PaymeTransaction
    {
        $account = $this->getAccountOrError($requestDto);
        $transaction = $this->findTransactionOrError((int)$account->getTransactionId());

        if ($transaction->getAmount() !== $requestDto->getParams()->getAmount()) {
            throw new PaymeException(PaymeExceptionText::WRONG_AMOUNT_EN, PaymeException::WRONG_AMOUNT);
        }

        switch ($transaction->getState()) {
            case PaymeTransactionState::FINISHED:
            case PaymeTransactionState::CANCELED_AFTER_FINISH:
            case PaymeTransactionState::CANCELED:
                throw new PaymeException(PaymeExceptionText::WRONG_STATE_EN, PaymeException::CANNOT_PROCESS_OPERATION);
        }

        if ($this->isTimeout($transaction->getCreateTime())) {
            $this->markTransactionAsTimeout($transaction, $this->transactionManager);
            throw new PaymeException(
                PaymeExceptionText::TRANSACTION_TIMEOUT_EN,
                PaymeException::CANNOT_PROCESS_OPERATION
            );
        }

        if ($transaction->getPaymeId() !== null && $transaction->getPaymeId() !== $requestDto->getParams()->getId()) {
            throw new PaymeException(
                PaymeExceptionText::TRANSACTION_ALREADY_HAS_PAYME_ID_EN,
                PaymeException::TRANSACTION_ALREADY_HAS_PAYME_ID
            );
        }

        $transaction->setState(PaymeTransactionState::CREATED);
        $transaction->setPaymeId($requestDto->getParams()->getId());
        $transaction->setTime($requestDto->getParams()->getTime());
        $this->transactionManager->save($transaction, true);

        return $transaction;
    }
}
