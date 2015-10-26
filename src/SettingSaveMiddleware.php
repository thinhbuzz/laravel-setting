<?php


namespace Buzz;


use Closure;
use Illuminate\Contracts\Foundation\Application;

class SettingSaveMiddleware
{
    /**
     * @var LaravelSetting
     */
    private $setting;

    public function __construct(Application $app)
    {

        $this->setting = $app->make('LaravelSetting');
    }

    /**
     * Save setting after filter.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $this->setting->save();

        return $response;
    }
}