<?php
declare(strict_types=1);

namespace Vim\Settings\Service;

use Doctrine\ORM\EntityManagerInterface;
use Vim\Settings\Dto\SettingInterface;
use Vim\Settings\Entity\ArraySettings;
use Vim\Settings\Entity\BooleanSettings;
use Vim\Settings\Entity\ChoseSettings;
use Vim\Settings\Entity\FloatSettings;
use Vim\Settings\Entity\IntegerSettings;
use Vim\Settings\Entity\StringSettings;
use Vim\Settings\Entity\TextSettings;
use Vim\Settings\Exception\SettingNotFoundException;
use Vim\Settings\Repository\SettingsRepository;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class SettingsService implements SettingsServiceInterface
{
    public function __construct(
        private AdapterInterface $cache,
        private SettingsCollection $settingsCollection,
        private SettingsRepository $settingsRepository,
        private EntityManagerInterface $em
    )
    {
    }

    public function get(string $code): array|bool|float|int|string|null
    {
        $cacheItem = $this->cache->getItem($this->getCacheKey($code));
        if (!$cacheItem->isHit()) {
            $setting = $this->settingsRepository->findOneByCode($code);
            if (null === $setting) {
                throw new SettingNotFoundException('Setting not found, code = "' . $code . '"');
            }

            $this->cache->save($cacheItem->set($setting->getValue()));
        }

        return $cacheItem->get();
    }

    public function save(string $code, string|array|bool|float|int|null $value): void
    {
        if (!($config = $this->settingsCollection->one($code))) {
            throw new \LogicException('The code "' . $code . '" not set in configuration');
        }

        $setting = $this->settingsRepository->findOneByCode($code);
        if (null === $setting) {
            $setting = match ($config->getType()) {
                SettingInterface::TYPE_STRING => new StringSettings(),
                SettingInterface::TYPE_TEXT => new TextSettings(),
                SettingInterface::TYPE_INTEGER => new IntegerSettings(),
                SettingInterface::TYPE_FLOAT => new FloatSettings(),
                SettingInterface::TYPE_BOOLEAN => new BooleanSettings(),
                SettingInterface::TYPE_ARRAY => new ArraySettings(),
                SettingInterface::TYPE_CHOICE => new ChoseSettings(),
                default => throw new \LogicException('Not found mapper for "' . $config->getType() . '"'),
            };

            $setting->setCode($code);
        }

        $setting->setValue($value);
        $this->em->persist($setting);
        $this->em->flush();
        $this->cache->deleteItem($this->getCacheKey($code));
        $this->get($code);
    }

    private function getCacheKey(string $code): string
    {
        return 'settings.' . $code;
    }
}
