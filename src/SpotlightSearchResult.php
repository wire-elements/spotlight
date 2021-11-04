<?php

namespace LivewireUI\Spotlight;

class SpotlightSearchResult
{
    protected mixed $id;
    protected string $name;
    protected ?string $description;
    protected array $synonyms = [];

    public function __construct(mixed $id, string $name, ?string $description, array $synonyms = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->synonyms = $synonyms;
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

    public function getSynonyms(): array
    {
        return $this->synonyms;
    }
}
