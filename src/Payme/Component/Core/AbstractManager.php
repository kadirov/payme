<?php

declare(strict_types=1);

namespace Kadirov\Payme\Component\Core;

use Kadirov\Payme\Entity\Interfaces\CreatedAtSettableInterface;
use Kadirov\Payme\Entity\Interfaces\UpdatedAtSettableInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(object $entity, bool $needToFlush = false): void
    {
        $this->updateCreatedOrUpdatedDates($entity);
        $this->getEntityManager()->persist($entity);

        if ($needToFlush) {
            $this->entityManager->flush();
        }
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    private function updateCreatedOrUpdatedDates(object $entity)
    {
        if ($entity->getId() === null && $entity instanceof CreatedAtSettableInterface) {
            $entity->setCreatedAt(new DateTime());
        } elseif ($entity->getId() !== null && $entity instanceof UpdatedAtSettableInterface) {
            $entity->setUpdatedAt(new DateTime());
        }
    }
}
