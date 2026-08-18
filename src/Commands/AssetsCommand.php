<?php

namespace LivewireUI\Spotlight\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use LivewireUI\Spotlight\SpotlightServiceProvider;

class AssetsCommand extends Command
{
    protected $signature = 'spotlight:assets';

    protected $description = 'Publish the Spotlight JavaScript and CSS assets to the public directory';

    public function handle(): int
    {
        $targetDirectory = public_path('vendor/spotlight/assets');

        if (! File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, recursive: true);
        }

        // Remove any previously published Spotlight assets (legacy unhashed
        // files and stale hashed builds from an older version) before
        // publishing the current build.
        foreach (File::glob($targetDirectory.'/spotlight*') as $stale) {
            File::delete($stale);
        }

        foreach (SpotlightServiceProvider::MANIFEST_ENTRIES as $source) {
            $file = SpotlightServiceProvider::resolveManifestFile($source);
            $sourcePath = __DIR__.'/../../public/build/'.$file;

            if (! file_exists($sourcePath)) {
                continue;
            }

            file_put_contents(public_path('vendor/spotlight/'.$file), file_get_contents($sourcePath));
        }

        $this->components->info('Spotlight assets published successfully.');

        return self::SUCCESS;
    }
}