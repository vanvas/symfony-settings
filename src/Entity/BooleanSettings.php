<?php
declare(strict_types=1);

namespace Vim\Settings\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BooleanSettings extends AbstractSettings
{
    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $value = null;

    public function getValue(): ?bool
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }
}
