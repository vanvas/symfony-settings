<?php
declare(strict_types=1);

namespace Vim\Settings\Middleware;

interface MiddlewareInterface
{
    public function handle(mixed $handleData, \Closure $next): mixed;
}
