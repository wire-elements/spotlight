<p align="center">
    <img src="https://user-images.githubusercontent.com/1133950/115727164-a806e700-a383-11eb-8605-9f7b56f987c6.png">
</p>

<p align="center">
<a href="https://github.com/livewire-ui/spotlight/actions"><img src="https://github.com/livewire-ui/spotlight/actions/workflows/run-tests.yml/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/livewire-ui/spotlight"><img src="https://img.shields.io/packagist/dt/livewire-ui/spotlight" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/livewire-ui/spotlight"><img src="https://img.shields.io/packagist/v/livewire-ui/spotlight" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/livewire-ui/spotlight"><img src="https://img.shields.io/packagist/l/livewire-ui/spotlight" alt="License"></a>
</p>

## About LivewireUI Spotlight

LivewireUI Spotlight is a Livewire component that provides Spotlight/Alfred-like functionality to your Laravel
application. <a href="https://twitter.com/Philo01/status/1380135839263559680?s=20">View demo video</a>.

## Installation

To get started, require the package via Composer:

```
composer require livewire-ui/spotlight
```

## Livewire directive

Add the Livewire directive `@livewire('livewire-ui-spotlight')`:

```html

<html>
<body>
<!-- content -->

@livewire('livewire-ui-spotlight')
</body>
</html>
```

## Opening Spotlight

To open the Spotlight input bar you can use one of the following shortcuts:

- CTRL + K
- CMD + K
- CTRL + /
- CMD + /

## Creating your first Spotlight command

You can create your first Spotlight command by creating a new class and have it
extend `LivewireUI\Spotlight\SpotlightCommand`. Start by defining a `$name` and `$description` for your command. The
name and description will be visible when searching through commands.

```php
<?php

namespace LivewireUI\Spotlight\Commands;

use LivewireUI\Spotlight\SpotlightCommand;

class Logout extends SpotlightCommand
{
    protected string $name = 'Logout';

    protected string $description = 'Logout out of your account';

}
```

The `execute` method is called when a command is chosen, and the command has no dependencies. Let's for example take a
look at the `Logout` command `execute` method:

```php
<?php

namespace LivewireUI\Spotlight\Commands;

use Illuminate\Contracts\Auth\StatefulGuard;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class Logout extends SpotlightCommand
{
    protected string $name = 'Logout';

    protected string $description = 'Logout out of your account';

    public function execute(Spotlight $spotlight, StatefulGuard $guard): void
    {
        $guard->logout();
        $spotlight->redirect('/');
    }
}
```

As you can see, you can type-hint your dependencies and have them resolved by Laravel. If you
type-hint `Spotlight $spotlight`, you will get access to the Livewire Spotlight component. This gives you access to all
the Livewire helpers, so you can redirect users, emit events, you name it.

## How to define command dependencies

In some cases your command might require dependencies. Let's say we want to create a new user and add it to a specific
team. In this case we would need to define a team dependency. To define any dependencies, add a new method to your
command and name the method `dependencies`.

You can use the `SpotlightCommandDependencies::collection()` method to create a new collection of dependencies. Call
the `add` method to register a new dependency. You can add as many of dependencies as you like. The user input prompt
follows the order in which you add the commands.

```php
SpotlightCommandDependencies::collection()
    ->add(SpotlightCommandDependency::make('team')->setPlaceholder('For which team do you want to create a user?'))
    ->add(SpotlightCommandDependency::make('foobar')->setPlaceholder('Some other dependency here?'));
```

For every dependency, Spotlight will check if a `search{dependency-name}` method exists on the command. This method
provides the search query given by the user. For example, to search for our team dependency:

```php
public function searchTeam($query)
{
    return Team::where('name', 'like', "%$query%")
        ->get()
        ->map(function(Team $team) {
            return new SpotlightSearchResult(
                $team->id,
                $team->name,
                sprintf('Create license for %s', $team->name)
            );
        });
}
```

Spotlight expects a collection of `SpotlightSearchResult` objects. The `SpotlightSearchResult` object consists out of
the result identifier, name and description.

Every dependency will have access to the already defined dependencies. So in the example below, you can see
that `searchFoobar` has access to the `Team the user has chosen. This allows for scoped dependency searching.

```php
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class CreateUser extends SpotlightCommand
{
    protected string $name = 'Create user';

    protected string $description = 'Create new team user';

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(SpotlightCommandDependency::make('team')->setPlaceholder('For which team do you want to create a user?'))
            ->add(SpotlightCommandDependency::make('foobar')->setPlaceholder('Some other dependency here?'));
    }

    public function searchTeam($query)
    {
        return Team::where('name', 'like', "%$query%")
            ->get()
            ->map(function(Team $team) {
                return new SpotlightSearchResult(
                    $team->id,
                    $team->name,
                    sprintf('Create user for %s', $team->name)
                );
            });
    }
    
    public function searchFoobar(Team $team, $query)
    {
        return $team->foobar()->where('name', 'like', "%$query%")
            ->get()
            ->map(function(Foobar $foobar) {
                return new SpotlightSearchResult(
                    $foobar->id,
                    $foobar->name,
                    sprintf('Create something for %s', $foobar->name)
                );
            });
    }

    public function execute(Spotlight $spotlight, Team $team)
    {
        $spotlight->emit('openModal', 'user-create', ['team' => $team->id]);
    }
        
}
```

## Register commands

You can register commands by adding these to the `livewire-ui-spotlight.php` config file:

```php
<?php

return [
    'placeholder' => 'What do you want to do?',
    'commands' => [
        \App\SpotlightCommands\CreateUser::class
    ]
];
```

It's also possible to register commands via one of your service providers:

```php
use \App\SpotlightCommands\CreateUser;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Spotlight::registerCommand(CreateUser::class);
    }

}
```

## Credits

- [Philo Hermans](https://github.com/philoNL)
- [All Contributors](../../contributors)

## License

Livewire UI is open-sourced software licensed under the [MIT license](LICENSE.md).
