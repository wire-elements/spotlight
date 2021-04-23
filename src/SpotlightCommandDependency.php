<?php

namespace LivewireUI\Spotlight;

class SpotlightCommandDependency
{
    public const SEARCH = 'search';
    public const INPUT = 'input';

    protected string $identifier;

    protected string $placeholder;

    protected string $type = 'search';

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public static function make(string $identifier): self
    {
        return new self($identifier);
    }

    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->identifier,
            'placeholder' => $this->placeholder,
            'type' => $this->type,
        ];
    }
}
