<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Placeholder
    |--------------------------------------------------------------------------
    |
    | Define the text you want to show by default when Spotlight is enabled
    |
    */

    'placeholder' => 'What do you want to do?',

    /*
    |--------------------------------------------------------------------------
    | Placeholder
    |--------------------------------------------------------------------------
    |
    | Define which commands you want to make available in Spotlight.
    | Alternatively, you can also register commands in your AppServiceProvider
    | with the Spotlight::registerCommand(Logout::class); method.
    |
    */

    'commands' => [
        \LivewireUI\Spotlight\Commands\Logout::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Include CSS
    |--------------------------------------------------------------------------
    |
    | Spotlight uses TailwindCSS, if you don't use TailwindCSS you will need
    | to set this parameter to true. This includes the modern-normalize css.
    |
    */
    'include_css' => false,
];
