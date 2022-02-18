<?php declare(strict_types=1);

namespace Kadirov\Entity\Interfaces;

use DateTimeInterface;

interface UpdatedAtSettableInterface
{
    public function setUpdatedAt(DateTimeInterface $dateTime);
}
