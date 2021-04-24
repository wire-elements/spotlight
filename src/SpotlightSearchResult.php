<?php

namespace LivewireUI\Spotlight;

class SpotlightSearchResult
{
    protected $id;
    protected string $name;
    protected ?string $description;

    public function __construct($id, string $name, ?string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId()
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
