<?php

declare(strict_types=1);

namespace Codeat3\BladePepicons;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BladePepiconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('blade-pepicons', []);

            $factory->add('pepicons', array_merge(['path' => __DIR__ . '/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-pepicons.php', 'blade-pepicons');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/svg' => public_path('vendor/blade-pepicons'),
            ], 'blade-pepicons');

            $this->publishes([
                __DIR__ . '/../config/blade-pepicons.php' => $this->app->configPath('blade-pepicons.php'),
            ], 'blade-pepicons-config');
        }
    }
}
