<?php
declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme\Api\Traits;

use Kadirov\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use Kadirov\Component\Billing\Payment\Payme\Dtos\PaymeRequestParamsAccountDto;
use Kadirov\Component\Billing\Payment\Payme\Exceptions\Constants\PaymeExceptionText;
use Kadirov\Component\Billing\Payment\Payme\Exceptions\PaymeException;

/**
 * Trait GetAccountTrait
 *
 * @package App\Components\Billing\Payment\Payme\Api\Traits
 */
trait GetAccountTrait
{
    /**
     * @param PaymeRequestDto $requestDto
     * @return PaymeRequestParamsAccountDto
     * @throws PaymeException
     */
    private function getAccountOrError(PaymeRequestDto $requestDto): PaymeRequestParamsAccountDto
    {
        $account = $requestDto->getParams()->getAccount();

        if ($account === null) {
            throw new PaymeException(
                PaymeExceptionText::ACCOUNT_IS_NOT_FOUND_EN,
                PaymeException::MISSING_REQUIRED_FIELDS
            );
        }

        return $account;
    }
}
