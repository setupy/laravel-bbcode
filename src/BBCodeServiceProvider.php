<?php

namespace Setupy\BBCode;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BBCodeServiceProvider extends ServiceProvider
{
    /**
     * Register the bbcode service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bbcode-parser', function () {
            return new BBCodeParser($this->getConfig());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['bbcode-parser'];
    }

    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([$this->getConfigPath() => config_path('bbcodes.php')], 'bbcodes-config');

        $this->setupBladeDirective();
    }

    /**
     * get package local config file path.
     *
     * @return string
     */
    private function getConfigPath()
    {
        return __DIR__ . '/config/bbcodes.php';
    }

    /**
     * Get config array.
     *
     * @return array
     */
    private function getConfig()
    {
        return config('bbcodes') ?: require_once $this->getConfigPath();
    }
    
    /**
     * Setup blade directive for bbcode parser.
     *
     * @return void
     */
    private function setupBladeDirective()
    {
        Blade::directive('bb', function ($bbcode) {
            return "<?php echo app('bbcode-parser')->parse($bbcode) ?>";
        });

        Blade::directive('bbexcept', function ($expression) {
            $expression = explode(',', $expression);
            return "<?php echo app('bbcode-parser')->except($expression[0])->parse($expression[1]) ?>";
        });

        Blade::directive('bbonly', function ($expression) {
            $expression = explode(',', $expression);
            return "<?php echo app('bbcode-parser')->only($expression[0])->parse($expression[1]) ?>";
        });
    }
}
