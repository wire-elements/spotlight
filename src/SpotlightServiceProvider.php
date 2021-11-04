<?php

namespace LivewireUI\Spotlight;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SpotlightServiceProvider extends PackageServiceProvider
{
    public static array $scripts = [];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('livewire-ui-spotlight')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasCommand(MakeSpotlightCommand::class);
    }

    public function bootingPackage(): void
    {
        Livewire::component('livewire-ui-spotlight', Spotlight::class);

        View::composer('livewire-ui-spotlight::spotlight', function ($view) {
            if (config('livewire-ui-spotlight.include_js', true)) {
                $view->jsPath = __DIR__.'/../public/spotlight.js';
            }

            if (config('livewire-ui-spotlight.include_css', false)) {
                $view->cssPath = __DIR__ . '/../public/spotlight.css';
            }
        });

        foreach (config('livewire-ui-spotlight.commands') as $command) {
            Spotlight::registerCommand($command);
        }
    }
}
