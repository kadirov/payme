<?php declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Dtos;

use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeCancelingReason;
use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeTransactionState;
use Symfony\Component\Serializer\Annotation\SerializedName;

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
    #[SerializedName('create_time')]
    private ?int $createTime = null;

    /**
     * Transaction performed time in our system
     */
    #[SerializedName('perform_time')]
    private ?int $performTime = null;

    /**
     * Timestamp. Transaction cancel time in our system
     */
    #[SerializedName('cancel_time')]
    private ?int $cancelTime = null;

    /**
     * Reason of canceling. A constant of {@see PaymeCancelingReason}
     * @see PaymeCancelingReason
     */
    private ?int $reason = null;

    private ?PaymeResponseDetailDto $detail = null;

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

    public function getCancelTime(): ?int
    {
        if ($this->cancelTime === null) {
            return null;
        }

        return $this->cancelTime * 1000;
    }

    public function setAllow(?bool $allow): PaymeResponseDto
    {
        $this->allow = $allow;
        return $this;
    }

    public function setTransaction(?string $transaction): PaymeResponseDto
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function setState(?int $state): PaymeResponseDto
    {
        $this->state = $state;
        return $this;
    }

    public function setCreateTime(?int $createTime): PaymeResponseDto
    {
        $this->createTime = $createTime;
        return $this;
    }

    public function setPerformTime(?int $performTime): PaymeResponseDto
    {
        $this->performTime = $performTime;
        return $this;
    }

    public function setCancelTime(?int $cancelTime): PaymeResponseDto
    {
        $this->cancelTime = $cancelTime;
        return $this;
    }

    public function getReason(): ?int
    {
        return $this->reason;
    }

    public function setReason(?int $reason): void
    {
        $this->reason = $reason;
    }

    public function getDetail(): ?PaymeResponseDetailDto
    {
        return $this->detail;
    }

    public function setDetail(?PaymeResponseDetailDto $detail): void
    {
        $this->detail = $detail;
    }
}
