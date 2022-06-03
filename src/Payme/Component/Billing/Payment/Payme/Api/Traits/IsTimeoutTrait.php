<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Billing\Payment\Payme\Api\Traits;

use DateInterval;
use DateTime;

trait IsTimeoutTrait
{
    private function isTimeout(int $time): bool
    {
        $paymeDatetime = new DateTime();
        $paymeDatetime->setTimestamp($time);
        $interval = new DateInterval('PT12H');
        $paymeDatetime->add($interval);

        return $paymeDatetime < (new DateTime());
    }
}
