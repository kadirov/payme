<?php

declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme\Api;

use Kadirov\Component\Billing\Payment\Payme\Api\Traits\TransactionTrait;
use Kadirov\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use Kadirov\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use Kadirov\Entity\PaymeTransaction;
use Kadirov\Repository\PaymeTransactionRepository;
use Doctrine\ORM\NonUniqueResultException;

class PaymeCheckTransaction
{
    use TransactionTrait;

    public function __construct(PaymeTransactionRepository $paymeTransactionRepository)
    {
        $this->setTransactionRepository($paymeTransactionRepository);
    }

    /**
     * @param PaymeRequestDto $requestDto
     * @return PaymeTransaction
     * @throws PaymeException
     * @throws NonUniqueResultException
     */
    public function findTransaction(PaymeRequestDto $requestDto): PaymeTransaction
    {
        return $this->findTransactionByPaymeId($requestDto->getParams()->getId());
    }
}
