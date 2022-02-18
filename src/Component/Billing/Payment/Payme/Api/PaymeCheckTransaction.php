<?php

declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme\Api;

use App\Component\Billing\Payment\Payme\Api\Traits\TransactionTrait;
use App\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use App\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use App\Entity\PaymeTransaction;
use App\Repository\PaymeTransactionRepository;
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
