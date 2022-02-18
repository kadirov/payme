<?php declare(strict_types=1);

namespace Kadirov\Entity\Interfaces;

use DateTimeInterface;

interface CreatedAtSettableInterface
{
    public function setCreatedAt(DateTimeInterface $dateTime);
}
