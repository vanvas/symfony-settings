<?php
declare(strict_types=1);

namespace Vim\Settings\Service;

interface SettingsServiceInterface
{
    public function get(string $code): array|bool|float|int|string|null;

    public function save(string $code, string|array|bool|float|int|null $value): void;
}
