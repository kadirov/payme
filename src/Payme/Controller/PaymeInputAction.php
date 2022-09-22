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
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use Kadirov\Payme\Component\Billing\Payment\Payme\PaymeAuthenticationChecker;
use Kadirov\Payme\Component\Billing\Payment\Payme\PaymeResponseErrorFactory;
use Kadirov\Payme\Controller\Base\AbstractController;
use Kadirov\Payme\Controller\Base\ResponseFormat;
use Kadirov\Payme\Entity\PaymeTransaction;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PaymeController
 *
 * @package App\Controller
 * @method PaymeRequestDto getDtoFromRequest(Request $request, string $dtoClass, string $format = ResponseFormat::JSONLD)
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
        private LoggerInterface $logger,
        private PaymeAuthenticationChecker $authenticationChecker,
        private PaymeResponseErrorFactory $responseErrorFactory,
    ) {
        parent::__construct($serializer, $validator);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(Request $request): Response
    {
        $this->logger->info('PAYME header:');
        $this->logger->info(print_r($request->headers->all(), true));

        $requestDto = $this->getDtoFromRequest($request, PaymeRequestDto::class);
        return $this->callMethodAndResponse($request, $requestDto);
    }

    /**
     * @param Request $request
     * @param PaymeRequestDto $requestDto
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws ExceptionInterface
     * @throws NonUniqueResultException
     * @throws NotFoundExceptionInterface
     */
    private function callMethodAndResponse(Request $request, PaymeRequestDto $requestDto): Response
    {
        try {
            $this->authenticationChecker->check($request);
            $responseDto = $this->callMethod($requestDto);
        } catch (PaymeException $exception) {
            $responseErrorDto = $this->responseErrorFactory->create($exception);
            return $this->responseNormalized(['error' => $responseErrorDto], format: ResponseFormat::JSON);
        }

        return $this->responseNormalized(['result' => $responseDto], format: ResponseFormat::JSON);
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
        $responseDto = new PaymeResponseDto();
        $transaction = null;

        $this->logger->info('(Payme) Method: ' . $requestDto->getMethod());

        switch ($requestDto->getMethod()) {
            case PaymeMethodType::CHECK_PERFORM_TRANSACTION:
                $this->checkPerformTransaction->check($requestDto, $responseDto);
                $responseDto->setAllow(true);
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

        if ($transaction !== null) {
            $this->updateResponse($responseDto, $transaction);
        }

        return $responseDto;
    }

    private function updateResponse(PaymeResponseDto $response, PaymeTransaction $transaction): void
    {
        $response->setCreateTime($transaction->getCreateTime());
        $response->setPerformTime($transaction->getPerformTime());
        $response->setCancelTime($transaction->getCancelTime());
        $response->setTransaction((string)$transaction->getId());
        $response->setState($transaction->getState());
        $response->setReason($transaction->getReason());
    }
}
