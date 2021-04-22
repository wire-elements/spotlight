const mix = require('laravel-mix');

    mix.js('resources/js/spotlight.js', 'public/')
        .postCss("resources/css/spotlight.css", "public/", [
            require("tailwindcss"),
        ]);
