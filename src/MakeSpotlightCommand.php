<?php

namespace LivewireUI\Spotlight;

use Illuminate\Console\GeneratorCommand;

class MakeSpotlightCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:spotlight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Spotlight command class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Spotlight command';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/spotlight.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Spotlight';
    }
}
