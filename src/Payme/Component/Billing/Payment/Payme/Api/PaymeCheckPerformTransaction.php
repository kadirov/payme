<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Api;

use Kadirov\Payme\Component\Billing\Payment\Payme\Api\Traits\GetAccountTrait;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\Traits\TransactionTrait;
use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeResponseDetailDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeResponseDetailItemDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeResponseDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\Constants\PaymeExceptionText;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use Kadirov\Payme\Component\Billing\Payment\Payme\PaymeTransactionManager;
use Kadirov\Payme\Repository\PaymeTransactionRepository;
use Psr\Log\LoggerInterface;

/**
 * First step.
 *
 * Payme ask us about correction when user going to pay.
 * If everything is ok then we must return ``[result => ["allow" => true]]``
 *
 * @package App\Components\Billing\Payment\Payme\Api
 */
class PaymeCheckPerformTransaction
{
    use GetAccountTrait;
    use TransactionTrait;

    public function __construct(
        private PaymeTransactionRepository $paymeTransactionRepository,
        private PaymeTransactionManager $transactionManager,
        private LoggerInterface $logger
    ) {
        $this->setTransactionRepository($paymeTransactionRepository);
    }

    /**
     * @param PaymeRequestDto $requestDto
     * @param PaymeResponseDto $responseDto
     * @throws PaymeException
     */
    public function check(PaymeRequestDto $requestDto, PaymeResponseDto $responseDto): void
    {
        $account = $this->getAccountOrError($requestDto);
        $transaction = $this->findTransactionOrError((int)$account->getTransactionId());

        $this->logger->info('(Payme) PaymeCheckPerformTransaction: transaction found');

        if ($transaction->getState() !== PaymeTransactionState::INITIAL) {
            throw new PaymeException(PaymeExceptionText::WRONG_STATE_EN, PaymeException::WRONG_USER_DATA);
        }

        if ($transaction->getAmount() !== (string)$requestDto->getParams()->getAmount()) {
            throw new PaymeException(PaymeExceptionText::WRONG_AMOUNT_EN, PaymeException::WRONG_AMOUNT);
        }

        $items = [];

        foreach ($transaction->getItems() as $item) {
            $items[] = new PaymeResponseDetailItemDto(
                $item->getCount(),
                $item->getTitle(),
                (int)$item->getPrice(),
                $item->getCode(),
                $item->getPackageCode(),
                $item->getVatPercent(),
            );
        }

        $responseDto->setDetail(new PaymeResponseDetailDto($items));
        $this->transactionManager->save($transaction, true);
    }
}
