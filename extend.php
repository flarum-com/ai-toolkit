<?php

namespace Flarum\Ai;

use Flarum\Extend as Flarum;

return [
    (new Flarum\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Flarum\Locales(__DIR__.'/resources/locale')),

    (new Flarum\Event)->subscribe(Interaction\ReplyToPosts::class),
];
