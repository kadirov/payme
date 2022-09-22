<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme;

use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\Constants\PaymeExceptionText;
use Kadirov\Payme\Component\Billing\Payment\Payme\Exceptions\PaymeException;
use Kadirov\Payme\Component\Core\ParameterGetter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

class PaymeAuthenticationChecker
{
    public function __construct(
        private LoggerInterface $logger,
        private ParameterGetter $parameterGetter
    ) {
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws PaymeException
     */
    public function check(Request $request): void
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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function checkIp(Request $request): void
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
