<?php declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Constants;

/**
 * Class CancelingReason
 *
 * @package App\Components\Billing\Payment\Payme\Constants
 */
class PaymeCancelingReason
{
    public const RECEIVER_IS_NOT_FOUND = 1;
    public const ERROR_ON_PURCHASE_AT_PROCESSING_CENTER = 2;
    public const TRANSACTION_ERROR = 3;
    public const TIMEOUT = 4;
    public const REFUND = 5;
    public const UNKNOWN_ERROR = 10;
}
