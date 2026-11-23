<?php

namespace LivewireUI\Spotlight\Tests\Fixtures;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class VisibleCommand extends SpotlightCommand
{
    public static bool $executed = false;

    public static bool $searched = false;

    protected string $name = 'Visible command';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(SpotlightCommandDependency::make('team')->setPlaceholder('Which team?'));
    }

    public function searchTeam($query)
    {
        self::$searched = true;

        return collect([new SpotlightSearchResult(1, 'Team', 'A team')]);
    }

    public function execute(Spotlight $spotlight): void
    {
        self::$executed = true;
    }

    public function shouldBeShown(): bool
    {
        return true;
    }
}
