<?php

declare(strict_types=1);

namespace Kadirov\Component\Core;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ParameterGetter
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function get(string $name): mixed
    {
        return $this->getParameter($name);
    }

    public function getString(string $name): string
    {
        return (string)$this->get($name);
    }

    public function getInt(string $name): int
    {
        return (int)$this->get($name);
    }

    public function getArray(string $name): array
    {
        return (array)$this->get($name);
    }

    public function getBool(string $name): bool
    {
        return (bool)$this->get($name);
    }

    public function getFloat(string $name): float
    {
        return (float)$this->get($name);
    }

    protected function getParameter(string $name): mixed
    {
        if (!$this->container->has('parameter_bag')) {
            throw new ServiceNotFoundException(
                'parameter_bag.',
                null,
                null,
                [],
                sprintf(
                    'The "%s::getParameter()" method is missing a parameter bag to work properly',
                    static::class
                )
            );
        }

        return $this->container->get('parameter_bag')->get($name);
    }
}
