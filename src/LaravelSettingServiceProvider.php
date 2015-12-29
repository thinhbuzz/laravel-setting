<?php


namespace Buzz;


use Illuminate\Support\ServiceProvider;

class LaravelSettingServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootConfig();
        $config = $this->app->config['setting'];
        $this->registerAlias($config['auto_alias']);
        $this->registerMiddleware($config['auto_save']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('LaravelSetting', function ($app) {
            return new LaravelSetting($app);
        });
    }

    /**
     * Set config file for package
     */
    protected function bootConfig()
    {
        $path = __DIR__ . '/../config/setting.php';
        $this->publishes([$path => config_path('setting.php')]);
        $this->mergeConfigFrom($path, 'setting');
    }

    /**
     * Add middleware auto save to application
     * @param $middleware
     */
    protected function registerMiddleware($save)
    {
        if ($save === true) {
            $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
            $kernel->pushMiddleware(SettingSaveMiddleware::class);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('LaravelSetting');
    }

    /**
     * @param $alias boolean
     */
    private function registerAlias($alias)
    {
        if ($alias === true) {
            \Illuminate\Foundation\AliasLoader::getInstance()
				->alias('Setting', LaravelSettingFacade::class);
        }
    }
}