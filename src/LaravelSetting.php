<?php


namespace Buzz;


use Illuminate\Foundation\Application;

class LaravelSetting
{
    /**
     * List all settings.
     *
     * @var array
     */
    protected $settings;
    /**
     * Flag change values.
     *
     * @var boolean
     */
    protected $isChange = false;
    /**
     * Config of package.
     *
     * @var array
     */
    protected $configPackage;
    /**
     * Instance of config.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $configApp;
    protected $defaultSetting = [];

    /**
     *
     * @param $pathSetting string to file
     */
    public function __construct(Application $app)
    {
        $this->configApp = $app->config;
        $this->configPackage = $this->configApp->get('setting');
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
    public function get($key, $default = false, $system = false)
    {
		if ($this->has($key)) {
			return array_get($this->settings, $key, $default);
		} elseif ($system === true || $this->configPackage['system_cnf'] === true){
			return  $this->configApp->get($key, $default);
		}
		return $default;
    }

    /**
     * Check exist setting
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_has($this->settings, $key);
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
        array_forget($this->settings, $keys);
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
        $this->saveSettings($this->defaultSetting);
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

    public function sync($key = false)
    {
        if ($key === false) {
            $this->settings = array_merge($this->settings, $this->configApp->all());
        } elseif (is_array($key)) {
            foreach ($key as $k) {
                $this->add($k, $this->configApp->get($k));
            }
        } else {
            $this->add($key, $this->configApp->get($key));
        }
        $this->isChange = true;
        $this->save();

        return $this;
    }

    /**
     * Save all change on settings
     */
    public function save($force = false)
    {
        if ($this->isChange === true || $this->configPackage['force_save'] === true || $force === true) {
            $this->saveSettings($this->settings);
        }

        return $this;
    }

    /**
     * Save settings
     * @param array $data
     */
    protected function saveSettings($data = [])
    {
        $data = (array)$data;
        file_put_contents($this->configPackage['path'], $this->prettyJsonEncode($data));
    }

    /**
     * Pertty json encode
     * @param $data
     * @return string
     */
    protected function prettyJsonEncode($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
    }
}