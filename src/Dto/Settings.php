<?php
declare(strict_types=1);

namespace Vim\Settings\Dto;

class Settings
{
    public const TYPE_TEXT = 'text';
    public const TYPE_CHOICE = 'choice';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_ARRAY = 'array';

    private string $type;

    private string $code;

    private string $name;

    private string|int|float|bool|array|null $value;

    private array $choiceList;

    public function __construct(string $type, string $code, string $name, $value, array $choiceList = [])
    {
        if (self::TYPE_CHOICE === $type && empty($choiceList)) {
            throw new \InvalidArgumentException('"$choseList" must not be empty.');
        }

        if (self::TYPE_BOOLEAN === $type && !is_bool($value)) {
            throw new \InvalidArgumentException('"$value" must be boolean.');
        }

        if (self::TYPE_ARRAY === $type && !is_array($value)) {
            throw new \InvalidArgumentException('"$value" must be array.');
        }

        $choiceList = array_map('strval', $choiceList);
        if (self::TYPE_TEXT == $type || self::TYPE_CHOICE === $type) {
            $value = (string) $value;
        }

        $this->type = $type;
        $this->code = $code;
        $this->name = $name;
        $this->value = $value;
        $this->choiceList = $choiceList;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getChoiceList(): array
    {
        return $this->choiceList;
    }

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
