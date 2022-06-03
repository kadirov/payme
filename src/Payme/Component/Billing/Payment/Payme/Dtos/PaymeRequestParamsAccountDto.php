<?php declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Dtos;

/**
 * Class RequestFromPaymeParamsAccountDto
 *
 * @package App\Components\Billing\Payment\Payme\Dtos
 */
class PaymeRequestParamsAccountDto
{
    private ?string $transactionId = null;

    /**
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @param string|null $transactionId
     */
    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }
}
