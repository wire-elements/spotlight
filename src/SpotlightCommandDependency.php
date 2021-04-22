<?php

namespace LivewireUI\Spotlight;

class SpotlightCommandDependency
{
    protected string $identifier;

    protected string $placeholder;

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

    public function toArray(): array
    {
        return [
            'id' => $this->identifier,
            'placeholder' => $this->placeholder,
        ];
    }
}
