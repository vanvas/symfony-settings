<?php
declare(strict_types=1);

namespace Vim\Settings\Middleware;

class ConfigsMiddlewareProcessor extends AbstractMiddlewareProcessor
{
    public function __construct(private \IteratorAggregate $middlewares)
    {
    }

    protected function getMiddlewares(): \IteratorAggregate
    {
        return $this->middlewares;
    }
}
