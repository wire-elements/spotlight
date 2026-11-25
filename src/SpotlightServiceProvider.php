<?php

namespace LivewireUI\Spotlight;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use LivewireUI\Spotlight\Commands\AssetsCommand;
use RuntimeException;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SpotlightServiceProvider extends PackageServiceProvider
{
    public const MANIFEST_ENTRIES = [
        'js' => 'resources/js/spotlight.js',
        'css' => 'resources/css/spotlight.css',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('livewire-ui-spotlight')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasCommand(MakeSpotlightCommand::class)
            ->hasCommand(AssetsCommand::class);
    }

    public function bootingPackage(): void
    {
        Livewire::component('livewire-ui-spotlight', Spotlight::class);

        $jsFile = static::resolveManifestFile(self::MANIFEST_ENTRIES['js']);
        $cssFile = static::resolveManifestFile(self::MANIFEST_ENTRIES['css']);

        $this->publishes([
            __DIR__.'/../public/build/'.$jsFile => public_path('vendor/spotlight/'.$jsFile),
            __DIR__.'/../public/build/'.$cssFile => public_path('vendor/spotlight/'.$cssFile),
        ], 'livewire-ui-spotlight-assets');

        View::composer('livewire-ui-spotlight::spotlight', function ($view) use ($jsFile, $cssFile) {
            $view->jsUrl = static::assetUrl($jsFile);
            $view->cssUrl = static::assetUrl($cssFile);
        });

        foreach (config('livewire-ui-spotlight.commands') as $command) {
            Spotlight::registerCommand($command);
        }
    }

    /**
     * Resolve the current hashed build filename (e.g. "assets/spotlight-C67w3Myj.js")
     * for a Vite entry, as recorded in the package's own build manifest.
     */
    public static function resolveManifestFile(string $source): string
    {
        static $manifest;

        $manifest ??= json_decode(file_get_contents(__DIR__.'/../public/build/manifest.json'), true);

        throw_unless(
            isset($manifest[$source]['file']),
            new RuntimeException("Unable to locate manifest entry for [{$source}].")
        );

        return $manifest[$source]['file'];
    }

    public static function assetUrl(string $file): string
    {
        $publishedPath = public_path('vendor/spotlight/'.$file);

        throw_unless(
            file_exists($publishedPath),
            new RuntimeException(
                "Unable to locate the published Spotlight asset [{$file}]. Run `php artisan spotlight:assets` to publish it."
            )
        );

        // The filename already contains a content hash, so no extra cache-busting query string is needed.
        return asset('vendor/spotlight/'.$file);
    }
}
