<?php
declare(strict_types=1);

namespace Vim\Attribute\Service;

use Vim\Attribute\Dto\Attributes;

class MiddlewareProcessor implements MiddlewareProcessorInterface
{
    private \IteratorAggregate $middlewares;

    public function __construct(\IteratorAggregate $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function process(Attributes $attributes): Attributes
    {
        /** @var \Generator $iterator */
        $iterator = $this->middlewares->getIterator();
        if (!$iterator->valid()) {
            return $attributes;
        }

        /** @var ConfigMiddlewareInterface|null $middleware */
        $middleware = $iterator->current();

        return $middleware->handle($attributes, $this->nextClosure($iterator));
    }

    private function nextClosure($iterator): callable
    {
        return function ($attributes) use ($iterator) {
            $iterator->next();
            if (!$iterator->valid()) {
                return $attributes;
            }

            return $iterator->current()->handle($attributes, $this->nextClosure($iterator));
        };
    }
}
