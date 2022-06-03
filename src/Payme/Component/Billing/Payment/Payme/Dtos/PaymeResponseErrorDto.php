<?php declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Dtos;

class PaymeResponseErrorDto
{
    private array $message;
    private int $code = 0;

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * @param string $en
     * @param string $uz
     * @param string $ru
     */
    public function setMessage(string $en, string $uz = '', string $ru = ''): void
    {
        $this->message = [
            'en' => $en,
            'uz' => $uz,
            'ru' => $ru,
        ];
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }
}
