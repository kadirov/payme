<?php

declare(strict_types=1);

namespace Kadirov\Component\Core;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ParameterGetter
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
            throw new ServiceNotFoundException('parameter_bag.', null, null, [], sprintf('The "%s::getParameter()" method is missing a parameter bag to work properly. Did you forget to register your controller as a service subscriber? This can be fixed either by using autoconfiguration or by manually wiring a "parameter_bag" in the service locator passed to the controller.', static::class));
        }

        return $this->container->get('parameter_bag')->get($name);
    }
}
