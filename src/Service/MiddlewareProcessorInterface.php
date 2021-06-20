<?php
declare(strict_types=1);

namespace Vim\Attribute\Service;

use Vim\Attribute\Dto\Attributes;

interface MiddlewareProcessorInterface
{
    public function process(Attributes $attributes): Attributes;
}
