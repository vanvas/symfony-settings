<?php
declare(strict_types=1);

namespace Vim\Settings\Factory;

use Vim\Settings\Dto\Settings;
use Vim\Settings\Service\SettingsCollection;

class SettingsCollectionFactory
{
    public function __construct(private array $config)
    {
    }

    public function create(): SettingsCollection
    {
        $items = [];
        foreach ($this->config as $config) {
            $items[] = new Settings(
                $config['type'],
                $config['code'],
                $config['name'],
                $config['value'],
                $config['choiceList'] ?? []
            );
        }

        return new SettingsCollection($items);
    }
}
