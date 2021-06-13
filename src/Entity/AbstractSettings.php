<?php
declare(strict_types=1);

namespace Vim\Settings\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vim\Settings\Repository\SettingsRepository;

/**
 * @ORM\Table(name="settings", uniqueConstraints={@ORM\UniqueConstraint(columns={"code"})})
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"text" = "TextSettings", "string" = "StringSettings", "boolean" = "BooleanSettings", "array" = "ArraySettings", "chose" = "ChoseSettings"})
 */
abstract class AbstractSettings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     */
    private ?string $code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|array|boolean
     */
    abstract public function getValue();

    /**
     * @param string|array|boolean $value
     */
    abstract public function setValue($value): void;
}
