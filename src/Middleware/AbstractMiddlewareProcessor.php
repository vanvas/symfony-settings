<?php
declare(strict_types=1);

namespace Vim\Settings\Middleware;

abstract class AbstractMiddlewareProcessor
{
    abstract protected function getMiddlewares(): \IteratorAggregate;

    public function process(mixed $handleData): mixed
    {
        /** @var \Generator $iterator */
        $iterator = $this->getMiddlewares()->getIterator();
        if (!$iterator->valid()) {
            return $handleData;
        }

        /** @var MiddlewareInterface|null $middleware */
        $middleware = $iterator->current();

        return $middleware->handle($handleData, $this->nextClosure($iterator));
    }

    private function nextClosure($iterator): callable
    {
        return function (mixed $handleData) use ($iterator) {
            $iterator->next();
            if (!$iterator->valid()) {
                return $handleData;
            }

            /** @var MiddlewareInterface|null $middleware */
            $middleware = $iterator->current();

            return $middleware->handle($handleData, $this->nextClosure($iterator));
        };
    }
}
