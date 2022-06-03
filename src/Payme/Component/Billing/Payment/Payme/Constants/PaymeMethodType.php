<?php declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Constants;

/**
 * Class PaymeMethodType
 *
 * @package App\Components\Billing\Payment\Payme\Constants
 */
class PaymeMethodType
{
    public const CHECK_PERFORM_TRANSACTION = 'CheckPerformTransaction';
    public const CREATE_TRANSACTION = 'CreateTransaction';
    public const PERFORM_TRANSACTION = 'PerformTransaction';
    public const CANCEL_TRANSACTION = 'CancelTransaction';
    public const CHECK_TRANSACTION = 'CheckTransaction';
    public const GET_STATEMENT = 'GetStatement';
    public const CHANGE_PASSWORD = 'ChangePassword';
}
