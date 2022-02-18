<?php declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme\Dtos;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RequestFromPaymeParamsDto
 *
 * @package App\Components\Billing\Payment\Payme\Dtos
 */
class PaymeRequestParamsDto
{
    /**
     * @Assert\Valid()
     */
    private ?PaymeRequestParamsAccountDto $account = null;

    /**
     * transaction id at payme, ex: 5305e3bab097f420a62ced0b
     */
    private ?string $id = null;

    /**
     * With cents, for example 5000 means 50.00
     */
    private ?int $amount = null;

    /**
     * Created at on payme
     */
    private ?int $time = null;

    /**
     * Reason of the canceling transaction
     */
    private ?int $reason = null;

    /**
     * Timestamp, for filtering transaction list
     */
    private ?int $from = null;

    /**
     * Timestamp, for filtering transaction list
     */
    private ?int $to = null;

    /**
     * @param PaymeRequestParamsAccountDto|null $account
     */
    public function setAccount(?PaymeRequestParamsAccountDto $account): void
    {
        $this->account = $account;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int|null $amount
     */
    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @param int|null $time
     */
    public function setTime(?int $time): void
    {
        $this->time = $time;
    }

    /**
     * @param int|null $reason
     */
    public function setReason(?int $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @param int|null $from
     */
    public function setFrom(?int $from): void
    {
        $this->from = $from;
    }

    /**
     * @param int|null $to
     */
    public function setTo(?int $to): void
    {
        $this->to = $to;
    }

    /**
     * @return PaymeRequestParamsAccountDto|null
     */
    public function getAccount(): ?PaymeRequestParamsAccountDto
    {
        return $this->account;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return int|null
     */
    public function getTime(): ?int
    {
        if ($this->time === null) {
            return null;
        }

        return (int)($this->time / 1000);
    }

    /**
     * @return int|null
     */
    public function getReason(): ?int
    {
        return $this->reason;
    }

    /**
     * @return int|null
     */
    public function getFrom(): ?int
    {
        if ($this->from === null) {
            return null;
        }

        return (int)($this->from / 1000);
    }

    /**
     * @return int|null
     */
    public function getTo(): ?int
    {
        if ($this->to === null) {
            return null;
        }

        return (int)($this->to / 1000);
    }

}
