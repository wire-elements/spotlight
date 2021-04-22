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
