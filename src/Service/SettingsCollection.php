<?php
declare(strict_types=1);

namespace Vim\Settings\Service;

use Vim\Settings\Dto\SettingInterface;

class SettingsCollection
{
    /**
     * @var SettingInterface[]
     */
    private array $items;

    public function __construct(array $items)
    {
        $codes = [];
        /** @var SettingInterface $item */
        foreach ($items as $item) {
            if (isset($codes[$item->getCode()])) {
                throw new \InvalidArgumentException('Duplicate code "'.$item->getCode().'"');
            }

            $codes[$item->getCode()] = $item->getCode();
        }

        unset($codes, $item);

        $this->items = $items;
    }

    public function one(string $code): ?SettingInterface
    {
        foreach ($this->items as $item) {
            if ($item->getCode() === $code) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @return SettingInterface[]
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return SettingInterface[]
     */
    public function codeStartsWith(string $needle): array
    {
        return array_filter(
            $this->items,
            static function (SettingInterface $setting) use ($needle) {
                return str_starts_with($setting->getCode(), $needle);
            },
        );
    }
}
