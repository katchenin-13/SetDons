<?php

/**
 * Génération de fil d'ariane
 */
namespace App\Service;

use Twig\Environment;

class FieldInfo
{
    private string $type;

    private array $props;

    private int $id;

    public function __construct(int $id, string $type, array $props)
    {
        $this->setType($type);
        $this->setProps($props);
        $this->setId($id);
    }

    /**
     * Get the value of type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of props
     */
    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * Set the value of props
     */
    public function setProps(array $props): self
    {
        $this->props = $props;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}

