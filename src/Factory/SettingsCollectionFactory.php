<?php
declare(strict_types=1);

namespace Vim\Settings\Factory;

use Vim\Settings\Dto\Setting;
use Vim\Settings\Middleware\ConfigsMiddlewareProcessor;
use Vim\Settings\Service\SettingsCollection;

class SettingsCollectionFactory
{
    public function __construct(private array $config, private ConfigsMiddlewareProcessor $middlewareProcessor)
    {
    }

    public function create(): SettingsCollection
    {
        $items = [];
        foreach ($this->config as $config) {
            $items[] = new Setting($config);
        }

        return new SettingsCollection(
            $this->middlewareProcessor->process($items)
        );
    }
}
