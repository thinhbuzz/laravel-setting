<?php


namespace Buzz;


use Illuminate\Foundation\Application;

class LaravelSetting
{
    protected $pathSetting;
    protected $settings;
    protected $isChange = false;
    protected $configPackage;
    protected $defaultSetting = [];

    /**
     *
     * @param $pathSetting string to file
     */
    public function __construct(Application $app)
    {
        $this->configPackage = $app->config['setting'];
        $this->load();
    }

    /**
     * Boot package
     */
    protected function load()
    {
        if (file_exists($this->configPackage['path']) === false) {
            $this->createDefault();
        }
        $settings = file_get_contents($this->configPackage['path']);
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
        $this->isChange = true;
        $this->settings = $data;
        return $this;
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
        $this->isChange = true;
        return $this;
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
        $this->isChange = true;
        return $this;
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
        $this->isChange = true;
        return $this;
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
    protected function createDefault()
    {
        $this->isChange = true;
        file_put_contents($this->configPackage['path'], json_encode($this->defaultSetting));
    }

    /**
     * Remove all settings
     */
    public function clean()
    {
        $this->isChange = true;
        $this->settings = [];
        return $this;
    }

    /**
     * Save all change on settings
     */
    public function save($force = false)
    {
        if ($this->isChange === true || $this->configPackage['force_save'] === true || $force === true) {
            file_put_contents($this->configPackage['path'], json_encode($this->settings));
        }
        return $this;
    }
}