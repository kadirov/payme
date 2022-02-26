<?php

declare(strict_types=1);

namespace Kadirov\Controller\Base;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Kadirov\Controller\Base\Constants\ResponseFormat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    protected function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param object $data
     * @param array $context
     * @throws ValidationException
     */
    protected function validate(object $data, array $context = []): void
    {
        $this->getValidator()->validate($data, $context);
    }

    protected function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    protected function response(
        mixed $content,
        int $status = Response::HTTP_OK,
        string $format = ResponseFormat::JSONLD
    ): Response {
        return (new Response(
            $this->getSerializer()->serialize($content, $format), $status
        ));
    }

    /**
     * @param int $status
     * @return Response
     */
    protected function responseEmpty(int $status = Response::HTTP_NO_CONTENT): Response
    {
        return $this->response('{}', $status);
    }

    /**
     * @throws ExceptionInterface
     */
    protected function responseNormalized(
        $content,
        int $status = Response::HTTP_OK,
        string $format = ResponseFormat::JSONLD
    ): Response {
        $result = $this->getSerializer()->normalize($content, $format);
        return $this->response($result, $status);
    }

    protected function getDtoFromRequest(
        Request $request,
        string $dtoClass,
        string $format = ResponseFormat::JSONLD
    ): object {
        return $this->getSerializer()->deserialize(
            $request->getContent(),
            $dtoClass,
            $format
        );
    }
}
