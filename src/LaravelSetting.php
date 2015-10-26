<?php


namespace Buzz;


class LaravelSetting
{
    protected $pathSetting;
    protected $settings;
    protected $defaultSetting = [
        'key' => 'value'
    ];

    /**
     *
     * @param $pathSetting string to file
     */
    public function __construct($pathSetting)
    {
        $this->pathSetting = $pathSetting;
        $this->load();
    }

    /**
     * Boot package
     */
    private function load()
    {
        if (file_exists($this->pathSetting) === false) {
            $this->createDefault();
        }
        $settings = file_get_contents($this->pathSetting);
        $decoded = json_decode($settings, true);
        $settingArray = is_null($decoded) ? $this->defaultSetting : $decoded;
        $this->settings = $settingArray;
    }

    /**
     * Overwrite settings
     * @param array $data
     */
    public function setData($data)
    {
        $this->settings = $data;
    }

    /**
     * Get setting value
     * @param string $key
     * @param bool $default
     * @return mixed
     */
    public function get($key, $default = false)
    {
        return array_get($this->settings, $key, $default);
    }

    /**
     * Check exist setting
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_get($this->settings, $key, false) !== false;
    }

    /**
     * Set value for setting with key
     * @param $key array|string
     * @param string $value
     */
    public function set($keys, $value = '')
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                array_set($this->settings, $key['name'], $key['value']);
            }
        } else {
            array_set($this->settings, $keys, $value);
        }
    }

    /**
     * Remove setting with key
     * @param $key array|string
     */
    public function remove($keys)
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                array_forget($this->settings, $key);
            }
        } else {
            array_forget($this->settings, $keys);
        }
    }

    /**
     * Add new setting
     * @param $key array|string
     * @param string $value
     * @return array
     */
    public function add($keys, $value = '')
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $this->settings = array_add($this->settings, $key['name'], $key['value']);
            }
        } else {
            $this->settings = array_add($this->settings, $keys, $value);
        }
    }

    /**
     * Get All settings
     * @return array
     */
    public function all()
    {
        return $this->settings;
    }

    /**
     * Load default setting file
     */
    private function createDefault()
    {
        file_put_contents($this->pathSetting, json_encode($this->defaultSetting));
    }

    public function clean($default = false)
    {
        if ($default)
            $this->settings = $this->defaultSetting;
        else
            $this->settings = [];
    }

    /**
     * Save all change on settings
     */
    public function save()
    {
        file_put_contents($this->pathSetting, json_encode($this->settings));
    }
}