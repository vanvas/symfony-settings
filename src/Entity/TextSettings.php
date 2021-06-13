<?php
declare(strict_types=1);

namespace Vim\Settings\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TextSettings extends AbstractSettings
{
    /**
     * @ORM\Column(type="text")
     */
    private ?string $value = null;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }
}
