<?php

namespace HDSSolutions\Laravel;

use HDSSolutions\Laravel\Modules\ModuleServiceProvider;
use HDSSolutions\Laravel\Models\BankAccountMovement;

class BankingModuleServiceProvider extends ModuleServiceProvider {

    protected array $middlewares = [
        \HDSSolutions\Laravel\Http\Middleware\BankingMenu::class,
    ];

    private $commands = [
        // \HDSSolutions\Laravel\Commands\SomeCommand::class,
    ];

    public function bootEnv():void {
        // enable config override
        $this->publishes([
            module_path('config/banking.php') => config_path('banking.php'),
        ], 'banking.config');

        // load routes
        $this->loadRoutesFrom( module_path('routes/banking.php') );
        // load views
        $this->loadViewsFrom( module_path('resources/views'), 'banking' );
        // load translations
        $this->loadTranslationsFrom( module_path('resources/lang'), 'banking' );
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
        app()->singleton(Banking::class, fn() => new Banking);
        // register commands
        $this->commands( $this->commands );
        // merge configuration
        $this->mergeConfigFrom( module_path('config/banking.php'), 'banking' );
        // alias models
        $this->alias('BankAccountMovement', BankAccountMovement::class);
    }

}
