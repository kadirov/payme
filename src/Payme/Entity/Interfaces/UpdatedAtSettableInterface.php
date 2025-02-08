<?php declare(strict_types=1);

namespace Kadirov\Payme\Entity\Interfaces;

use DateTimeInterface;

interface UpdatedAtSettableInterface
{
    public function setUpdatedAt(DateTimeInterface $dateTime);
}
