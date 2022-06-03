<?php declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Dtos;

use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeCancelingReason;
use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;

/**
 * Uses as response for Payme
 *
 * @package App\Components\Billing\Payment\Payme\Dtos
 */
class PaymeResponseDto
{
    /**
     * Set us true if is everything ok
     */
    private ?bool $allow = null;

    /**
     * Transaction id in our system
     */
    private ?string $transaction = null;

    /**
     * State of transaction in our system
     * A constant of {@see PaymeTransactionState}
     *
     * @see PaymeTransactionState
     */
    private ?int $state = null;

    /**
     * Transaction created time in our system
     */
    private ?int $createTime = null;

    /**
     * Transaction performed time in our system
     */
    private ?int $performTime = null;

    /**
     * Timestamp. Transaction cancel time in our system
     */
    private ?int $cancelTime = null;

    /**
     * Reason of canceling. A constant of {@see PaymeCancelingReason}
     * @see PaymeCancelingReason
     */
    private ?int $reason = null;

    /**
     * @return bool|null
     */
    public function getAllow(): ?bool
    {
        return $this->allow;
    }

    /**
     * @return string|null
     */
    public function getTransaction(): ?string
    {
        return $this->transaction;
    }

    /**
     * @return int|null
     */
    public function getState(): ?int
    {
        return $this->state;
    }

    /**
     * @return int|null
     */
    public function getCreateTime(): ?int
    {
        if ($this->createTime === null) {
            return null;
        }

        return $this->createTime * 1000;
    }

    /**
     * @return int|null
     */
    public function getPerformTime(): ?int
    {
        if ($this->performTime === null) {
            return null;
        }

        return $this->performTime * 1000;
    }

    /**
     * @return int|null
     */
    public function getCancelTime(): ?int
    {
        if ($this->cancelTime === null) {
            return null;
        }

        return $this->cancelTime * 1000;
    }

    /**
     * @param bool|null $allow
     * @return PaymeResponseDto
     */
    public function setAllow(?bool $allow): PaymeResponseDto
    {
        $this->allow = $allow;
        return $this;
    }

    /**
     * @param string|null $transaction
     * @return PaymeResponseDto
     */
    public function setTransaction(?string $transaction): PaymeResponseDto
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * @param int|null $state
     * @return PaymeResponseDto
     */
    public function setState(?int $state): PaymeResponseDto
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param int|null $createTime
     * @return PaymeResponseDto
     */
    public function setCreateTime(?int $createTime): PaymeResponseDto
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * @param int|null $performTime
     * @return PaymeResponseDto
     */
    public function setPerformTime(?int $performTime): PaymeResponseDto
    {
        $this->performTime = $performTime;
        return $this;
    }

    /**
     * @param int|null $cancelTime
     * @return PaymeResponseDto
     */
    public function setCancelTime(?int $cancelTime): PaymeResponseDto
    {
        $this->cancelTime = $cancelTime;
        return $this;
    }

    /**
     * @param PaymeResponseErrorDto|null $error
     */
    public function setError(?PaymeResponseErrorDto $error): void
    {
        $this->error = $error;
    }

    /**
     * @return int|null
     */
    public function getReason(): ?int
    {
        return $this->reason;
    }

    /**
     * @param int|null $reason
     */
    public function setReason(?int $reason): void
    {
        $this->reason = $reason;
    }
}
