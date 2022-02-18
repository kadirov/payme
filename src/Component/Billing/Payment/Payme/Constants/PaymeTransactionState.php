<?php declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme\Constants;

/**
 * Class TransactionState
 *
 * @package App\Components\Billing\Payment\Payme\Constants
 */
class PaymeTransactionState
{
    public const INITIAL = 0;

    public const CREATED = 1;
    public const FINISHED = 2;

    public const CANCELED = -1;
    public const CANCELED_AFTER_FINISH = -2;
}
