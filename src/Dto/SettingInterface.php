<?php
declare(strict_types=1);

namespace Vim\Settings\Dto;

interface SettingInterface
{
    public const TYPE_INTEGER = 'integer';
    public const TYPE_FLOAT = 'float';
    public const TYPE_STRING = 'string';
    public const TYPE_TEXT = 'text';
    public const TYPE_NUMBER = 'number';
    public const TYPE_CHOICE = 'choice';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_ARRAY = 'array';

    public function getType(): string;

    public function getCode(): string;

    public function getName(): string;

    public function getValue(): string|int|float|bool|array|null;

    public function getChoiceList(): array;

    public function getConfig(): array;

    public function toArray(): array;
}
