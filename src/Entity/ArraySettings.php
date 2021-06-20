<?php
declare(strict_types=1);

namespace Vim\Settings\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ArraySettings extends AbstractSettings
{
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $value = null;

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(array $value): void
    {
        $this->value = $value;
    }
}
