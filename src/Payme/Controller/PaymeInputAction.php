<?php

declare(strict_types=1);

namespace Kadirov\Payme\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Doctrine\ORM\NonUniqueResultException;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\PaymeCancelTransaction;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\PaymeCheckPerformTransaction;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\PaymeCheckTransaction;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\PaymeCreateTransaction;
use Kadirov\Payme\Component\Billing\Payment\Payme\Api\PaymePerformTransaction;
use Kadirov\Payme\Component\Billing\Payment\Payme\Constants\PaymeMethodType;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeRequestDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeResponseDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Dtos\PaymeResponseErrorDto;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\Constants\PaymeExceptionText;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use Kadirov\Payme\Component\Core\ParameterGetter;
use Kadirov\Payme\Controller\Base\AbstractController;
use Kadirov\Payme\Controller\Base\ResponseFormat;
use LogicException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PaymeController
 *
 * @package App\Controller
 */
class PaymeInputAction extends AbstractController
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        private PaymeCheckPerformTransaction $checkPerformTransaction,
        private PaymeCreateTransaction $createTransaction,
        private PaymePerformTransaction $performTransaction,
        private PaymeCheckTransaction $checkTransaction,
        private PaymeCancelTransaction $cancelTransaction,
        private ParameterGetter $parameterGetter,
        private LoggerInterface $logger,
    ) {
        parent::__construct($serializer, $validator);
    }

    /**
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     */
    public function __invoke(Request $request): Response
    {
        $this->logger->info('PAYME header:');
        $this->logger->info(print_r($request->headers->all(), true));

        /** @var PaymeRequestDto $requestDto */
        $requestDto = $this->getDtoFromRequest($request, PaymeRequestDto::class);

        $this->logger->info('call method');
        return $this->callMethodAndResponse($request, $requestDto);
    }

    /**
     * @param PaymeRequestDto $requestDto
     * @return PaymeResponseDto
     * @throws PaymeException
     * @throws NonUniqueResultException
     */
    private function callMethod(PaymeRequestDto $requestDto): PaymeResponseDto
    {
        $this->validate($requestDto);
        $response = new PaymeResponseDto();
        $transaction = null;

        $this->logger->info('(Payme) Method: ' . $requestDto->getMethod());

        switch ($requestDto->getMethod()) {
            case PaymeMethodType::CHECK_PERFORM_TRANSACTION:
                $this->checkPerformTransaction->check($requestDto);
                $response->setAllow(true);
                break;

            case PaymeMethodType::CREATE_TRANSACTION:
                $transaction = $this->createTransaction->check($requestDto);
                break;

            case PaymeMethodType::PERFORM_TRANSACTION:
                $transaction = $this->performTransaction->check($requestDto);
                break;

            case PaymeMethodType::CHECK_TRANSACTION:
                $transaction = $this->checkTransaction->findTransaction($requestDto);
                break;

            case PaymeMethodType::CANCEL_TRANSACTION:
                $transaction = $this->cancelTransaction->cancel($requestDto);
                break;
        }

        $this->logger->info('(Payme) prepare $response');

        if ($transaction !== null) {
            $response->setCreateTime($transaction->getCreateTime());
            $response->setPerformTime($transaction->getPerformTime());
            $response->setCancelTime($transaction->getCancelTime());
            $response->setTransaction((string)$transaction->getId());
            $response->setState($transaction->getState());
            $response->setReason($transaction->getReason());
        }

        $this->logger->info('(Payme) before return response');

        return $response;
    }

    /**
     * @param Request $request
     * @param PaymeRequestDto $requestDto
     * @return Response
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     */
    private function callMethodAndResponse(Request $request, PaymeRequestDto $requestDto): Response
    {
        try {
            $this->authorization($request);
            $responseDto = $this->callMethod($requestDto);
        } catch (PaymeException $exception) {
            $responseErrorDto = new PaymeResponseErrorDto();
            $responseErrorDto->setCode($exception->getCode());
            $this->fillErrorMessage($responseErrorDto, $exception);
            return $this->responseNormalized(['error' => $responseErrorDto], Response::HTTP_OK, ResponseFormat::JSON);
        }

        $cc2sc = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());

        $this->logger->info('(Payme) created ObjectNormalizer');

        return $this->responseNormalized(
            ['result' => $cc2sc->normalize($responseDto)],
            Response::HTTP_OK,
            ResponseFormat::JSON
        );
    }

    private function fillErrorMessage(PaymeResponseErrorDto $errorDto, PaymeException $exception): void
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

    /**
     * @param Request $request
     * @throws PaymeException
     */
    private function authorization(Request $request): void
    {
        $this->logger->info('auth');
        $this->checkIp($request);

        $encoded = $request->headers->get('Authorization');

        if ($encoded === null) {
            throw new PaymeException(PaymeExceptionText::UNAUTHORIZED_EN, PaymeException::UNAUTHORIZED);
        }

        $encoded = str_ireplace('basic ', '', $encoded);

        if ($encoded === null) {
            throw new PaymeException(PaymeExceptionText::UNAUTHORIZED_EN, PaymeException::UNAUTHORIZED);
        }

        $decoded = base64_decode($encoded);

        if ($decoded === false) {
            throw new PaymeException(PaymeExceptionText::UNAUTHORIZED_EN, PaymeException::UNAUTHORIZED);
        }

        $auth = explode(':', $decoded);

        if (empty($auth[0]) || empty($auth[1])) {
            throw new PaymeException(PaymeExceptionText::UNAUTHORIZED_EN, PaymeException::UNAUTHORIZED);
        }

        $login = $this->parameterGetter->getString('payme_cashbox_id');
        $key = $this->parameterGetter->getString('payme_cashbox_key');

        if (($auth[0] === $login || $auth[0] === 'Paycom') && $auth[1] === $key) {
            return;
        }

        $testLogin = $this->parameterGetter->getString('payme_cashbox_test_id');
        $testKey = $this->parameterGetter->getString('payme_cashbox_test_key');
        $appEnv = $request->server->get('APP_ENV');

        if ($appEnv === 'dev' && $auth[0] === $testLogin && $auth[1] === $testKey) {
            return;
        }

        throw new PaymeException(PaymeExceptionText::UNAUTHORIZED_EN, PaymeException::UNAUTHORIZED);
    }

    private function checkIp(Request $request)
    {
        if (!$this->parameterGetter->getBool('payme_check_ips')) {
            return;
        }

        $requestIp = $request->headers->get('X-Forwarded-For');

        if ($requestIp === null) {
            throw new RuntimeException('Can not detect request IP');
        }

        if (!in_array($requestIp, $this->parameterGetter->getArray('payme_ips'), true)) {
            throw new RuntimeException('Wrong IP address');
        }
    }
}
