<?php

$providers = [
    App\Providers\AppServiceProvider::class,
    App\Providers\ContentServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];

if (class_exists(Laravel\Telescope\TelescopeApplicationServiceProvider::class)) {
    $providers[] = App\Providers\TelescopeServiceProvider::class;
}

return $providers;
