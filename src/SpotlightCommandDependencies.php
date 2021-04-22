<?php

namespace LivewireUI\Spotlight;

use Illuminate\Support\Collection;

class SpotlightCommandDependencies
{
    protected Collection $dependencies;

    public function __construct()
    {
        $this->dependencies = new Collection([]);
    }

    public function add(SpotlightCommandDependency $dependency): self
    {
        $this->dependencies->push($dependency);

        return $this;
    }

    public function toArray(): array
    {
        return $this->dependencies->map(fn ($dep) => $dep->toArray())->toArray();
    }

    public static function collection(): self
    {
        return new self;
    }
}
