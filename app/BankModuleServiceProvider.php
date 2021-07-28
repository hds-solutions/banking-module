<?php

namespace HDSSolutions\Laravel;

use HDSSolutions\Laravel\Modules\ModuleServiceProvider;

class BankModuleServiceProvider extends ModuleServiceProvider {

    protected array $middlewares = [
        \HDSSolutions\Laravel\Http\Middleware\BankMenu::class,
    ];

    private $commands = [
        // \HDSSolutions\Laravel\Commands\SomeCommand::class,
    ];

    public function bootEnv():void {
        // enable config override
        $this->publishes([
            module_path('config/bank.php') => config_path('bank.php'),
        ], 'bank.config');

        // load routes
        $this->loadRoutesFrom( module_path('routes/bank.php') );
        // load views
        $this->loadViewsFrom( module_path('resources/views'), 'bank' );
        // load translations
        $this->loadTranslationsFrom( module_path('resources/lang'), 'bank' );
        // load migrations
        $this->loadMigrationsFrom( module_path('database/migrations') );
        // load seeders
        $this->loadSeedersFrom( module_path('database/seeders') );
    }

    public function register() {
        // register helpers
        if (file_exists($helpers = realpath(__DIR__.'/helpers.php')))
            //
            require_once $helpers;
        // register singleton
        // app()->singleton(Bank::class, fn() => new Bank);
        // register commands
        $this->commands( $this->commands );
        // merge configuration
        $this->mergeConfigFrom( module_path('config/bank.php'), 'bank' );
    }

}
