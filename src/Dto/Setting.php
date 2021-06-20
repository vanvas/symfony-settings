<?php
declare(strict_types=1);

namespace Vim\Settings\Dto;

use JetBrains\PhpStorm\ArrayShape;

class Setting implements SettingInterface
{
    public function __construct(private array $config)
    {
        if (SettingInterface::TYPE_CHOICE === $this->getType() && empty($config['choiceList'])) {
            throw new \InvalidArgumentException('"$choseList" must not be empty.');
        }

        $value = $this->getValue();

        if (SettingInterface::TYPE_BOOLEAN === $this->getType() && null !== $value && !is_bool($value)) {
            throw new \InvalidArgumentException('"$value" must be boolean.');
        }

        if (SettingInterface::TYPE_ARRAY === $this->getType() && null !== $value && !is_array($value)) {
            throw new \InvalidArgumentException('"$value" must be array.');
        }
    }

    public function getType(): string
    {
        return $this->config['type'];
    }

    public function getCode(): string
    {
        return $this->config['code'];
    }

    public function getName(): string
    {
        return $this->config['name'];
    }

    public function getValue(): string|int|float|bool|array|null
    {
        if (isset($this->config['value'])) {
            return $this->config['value'];
        }

        return match($this->getType()) {
            SettingInterface::TYPE_BOOLEAN => false,
            SettingInterface::TYPE_ARRAY => [],
            default => null,
        };
    }

    public function getChoiceList(): array
    {
        return array_map('strval', $this->config['choiceList'] ?? []);
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    #[ArrayShape(['type' => "string", 'code' => "string", 'name' => "string", 'value' => "mixed", 'choseList' => "array"])]
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'choseList' => $this->getChoiceList(),
        ];
    }
}
