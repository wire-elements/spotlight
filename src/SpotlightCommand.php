<?php

namespace LivewireUI\Spotlight;

abstract class SpotlightCommand
{
    protected string $name;

    protected string $description;

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return null;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return md5(static::class);
    }
}
