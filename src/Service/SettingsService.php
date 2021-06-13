<?php
declare(strict_types=1);

namespace Vim\Settings\Service;

use Vim\Settings\Repository\SettingsRepository;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class SettingsService
{
    public function __construct(
        private AdapterInterface $cache,
        private SettingsCollection $settingsCollection,
        private SettingsRepository $settingsRepository
    )
    {
    }

    private function getWithoutCache(string $code): array|bool|float|int|string|null
    {
        $settings = $this->settingsRepository->findOneByCode($code);
        if (null === $settings) {
            $settings = $this->settingsCollection->one($code);

            return $settings->getValue();
        }

        return $settings->getValue();
    }

    public function get(string $code): array|bool|float|int|string|null
    {
        $cacheItem = $this->cache->getItem($this->getCacheKey($code));
        if (!$cacheItem->isHit()) {
            $this->cache->save($cacheItem->set($this->getWithoutCache($code)));
        }

        return $cacheItem->get();
    }

    public function refreshCache(string $code): void
    {
        $this->cache->deleteItem($this->getCacheKey($code));
        $this->get($code);
    }

    public function getAll(): array
    {
        $result = [];
        foreach ($this->settingsCollection->all() as $settings) {
            $result[] = array_merge($settings->toArray(), ['value' => $this->get($settings->getCode())]);
        }

        return $result;
    }


    private function getCacheKey(string $code): string
    {
        return 'settings.' . $code;
    }
}
