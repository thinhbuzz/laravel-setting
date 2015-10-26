<?php


namespace Buzz;


use Illuminate\Support\Facades\Facade;

class LaravelSettingFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LaravelSetting';
    }
}