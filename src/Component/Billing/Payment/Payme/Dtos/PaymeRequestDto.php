<?php declare(strict_types=1);

namespace Kadirov\Component\Billing\Payment\Payme\Dtos;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Using for requests from Payme service to our backend
 *
 * @package App\Components\Billing\Payment\Payme\Dtos
 */
class PaymeRequestDto
{
    /**
     * @Assert\NotBlank()
     */
    private string $method;

    /**
     * @Assert\Valid()
     * @Assert\NotBlank()
     */
    private PaymeRequestParamsDto $params;

    public function setParams(PaymeRequestParamsDto $params): void
    {
        $this->params = $params;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParams(): PaymeRequestParamsDto
    {
        return $this->params;
    }
}
