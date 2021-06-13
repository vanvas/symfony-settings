<?php
declare(strict_types=1);

namespace Vim\Settings\Service;

use Vim\Settings\Dto\Settings;

class SettingsCollection
{
    /**
     * @var Settings[]
     */
    private array $items;

    public function __construct(array $items)
    {
        $codes = [];
        /** @var Settings $item */
        foreach ($items as $item) {
            if (isset($codes[$item->getCode()])) {
                throw new \InvalidArgumentException('Duplicate code "'.$item->getCode().'"');
            }

            $codes[$item->getCode()] = $item->getCode();
        }

        unset($codes, $item);

        $this->items = $items;
    }

    public function one(string $code): Settings
    {
        foreach ($this->items as $item) {
            if ($item->getCode() === $code) {
                return $item;
            }
        }
    }

    /**
     * @return Settings[]
     */
    public function all(): array
    {
        return $this->items;
    }
}
