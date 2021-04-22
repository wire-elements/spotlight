<?php

namespace LivewireUI\Spotlight;

class SpotlightSearchResult
{
    protected mixed $id;
    protected string $name;
    protected ?string $description;

    public function __construct(mixed $id, string $name, ?string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
