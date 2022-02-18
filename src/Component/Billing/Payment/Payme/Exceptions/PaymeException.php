<?php

declare(strict_types=1);

namespace App\Component\Billing\Payment\Payme\Exceptions;

use Exception;

class PaymeException extends Exception
{
    /**
     * Missing required fields
     */
    public const MISSING_REQUIRED_FIELDS = -32600;

    /**
     * User data at paying process and data in our database are different
     */
    public const WRONG_USER_DATA = -31050;

    /**
     * Amount at paying process in our database are different
     */
    public const WRONG_AMOUNT = -31001;

    /**
     * Cannot process operation
     */
    public const CANNOT_PROCESS_OPERATION = -38001;

    /**
     * Transaction is not found
     */
    public const TRANSACTION_IS_NOT_FOUND = -31003;

    /**
     * If transaction has paymeId but payme tries to assign another for it
     */
    public const TRANSACTION_ALREADY_HAS_PAYME_ID = -31060;

    /**
     * Transaction has finished and cannot be refunded
     */
    public const TRANSACTION_FINISHED_AND_CANNOT_BE_CANCELED = -31007;

    /**
     * Unauthorized
     */
    public const UNAUTHORIZED = -32504;
}
