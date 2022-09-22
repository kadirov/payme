<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme;

use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeResponseErrorDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\Constants\PaymeExceptionText;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use LogicException;

class PaymeResponseErrorFactory
{
    public function create(PaymeException $exception): PaymeResponseErrorDto
    {
        $responseErrorDto = new PaymeResponseErrorDto();
        $responseErrorDto->setCode($exception->getCode());
        $this->fill($responseErrorDto, $exception);

        return $responseErrorDto;
    }

    public function fill(PaymeResponseErrorDto $errorDto, PaymeException $exception): void
    {
        switch ($exception->getMessage()) {
            case PaymeExceptionText::WRONG_STATE_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::WRONG_STATE_UZ,
                    PaymeExceptionText::WRONG_STATE_RU
                );
                break;

            case PaymeExceptionText::TRANSACTION_TIMEOUT_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::TRANSACTION_TIMEOUT_UZ,
                    PaymeExceptionText::TRANSACTION_TIMEOUT_RU
                );
                break;

            case PaymeExceptionText::TRANSACTION_IS_NOT_FOUND_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::TRANSACTION_IS_NOT_FOUND_UZ,
                    PaymeExceptionText::TRANSACTION_IS_NOT_FOUND_RU
                );
                break;

            case PaymeExceptionText::ID_IS_NOT_FOUND_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::ID_IS_NOT_FOUND_UZ,
                    PaymeExceptionText::ID_IS_NOT_FOUND_RU
                );
                break;

            case PaymeExceptionText::ACCOUNT_IS_NOT_FOUND_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::ACCOUNT_IS_NOT_FOUND_UZ,
                    PaymeExceptionText::ACCOUNT_IS_NOT_FOUND_RU
                );
                break;

            case PaymeExceptionText::WRONG_AMOUNT_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::WRONG_AMOUNT_UZ,
                    PaymeExceptionText::WRONG_AMOUNT_RU
                );
                break;

            case PaymeExceptionText::TRANSACTION_ALREADY_HAS_PAYME_ID_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::TRANSACTION_ALREADY_HAS_PAYME_ID_UZ,
                    PaymeExceptionText::TRANSACTION_ALREADY_HAS_PAYME_ID_RU
                );
                break;

            case PaymeExceptionText::TRANSACTION_FINISHED_AND_CANNOT_BE_CANCELED_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::TRANSACTION_FINISHED_AND_CANNOT_BE_CANCELED_UZ,
                    PaymeExceptionText::TRANSACTION_FINISHED_AND_CANNOT_BE_CANCELED_RU
                );
                break;

            case PaymeExceptionText::UNAUTHORIZED_EN:
                $errorDto->setMessage(
                    $exception->getMessage(),
                    PaymeExceptionText::UNAUTHORIZED_UZ,
                    PaymeExceptionText::UNAUTHORIZED_RU
                );
                break;

            default:
                throw new LogicException('Payme Exception Text is not found');
        }
    }
}
