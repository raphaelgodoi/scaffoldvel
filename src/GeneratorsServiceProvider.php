<?php

namespace RaphaGodoi\ScaffoldVel;

use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerScaffoldGenerator();
	}

	/**
	 * Register the make:scaffold generator.
	 *
	 * @return void
	 */
	private function registerScaffoldGenerator()
	{
		$this->app->singleton('command.raphagodoi.scaffold', function ($app) {
			return $app['RaphaGodoi\ScaffoldVel\Commands\ScaffoldMakeCommand'];
		});

		$this->commands('command.raphagodoi.scaffold');
	}

}
