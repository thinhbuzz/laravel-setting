# Package changes config for laravel 5.*

## Cài đặt
Mở file `composer.json` và thêm `require`:

```json
{
    "require": {
        "buzz/laravel-setting": "1.*"
    }
}
```

Sau đó chạy lệnh ```composer update``` để cài đặt.

hoặc chạy command sau để tự động thêm vào `composer.json` và cài đặt:

```
composer require buzz/laravel-setting
```


Mở file `config/app.php` và thêm ServiceProvider.

```
'providers' => [
    //.....
    Illuminate\Validation\ValidationServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,
    //.....
    \Buzz\LaravelSettingServiceProvider::class,
],
```


### Cấu hình

Tạo file cấu hình ``config/setting.php`` bằng lệnh sau:

~~~
php artisan vendor:publish --provider="Buzz\LaravelSettingServiceProvider"
~~~

hoặc

~~~
php artisan vendor:publish
~~~

##### setting.php
```
<?php
return [
    'path' => storage_path('settings.json'),//đường dẫn tới file `setting.json`, nên đặt ở `storage` hoặc `resources`
    'auto_alias' => true,//tự động tạo thêm alias `Setting`
    'auto_save' => true,//tự động save sau khi kết thúc request (sẽ không hoạt động nếu sử dụng `exit` hoặc `die`)
    'force_save' => false,//bắt buộc gọi save dù không thực hiện thao tác add, set, remove
    'system_cnf' => false,//get config của app nếu trong setting không tồn tại
];
```


## Sử dụng

#### Thay đổi alias

Mặc định package sẽ thêm alias `Setting` và bạn chỉ việc sử dụng alias này, nếu bạn muốn thay đổi alias thì sửa `'auto_alias'=>false` và thêm trong `aliases` của `config/app.php`

```
'aliases' => [
    //.....
    'Validator' => Illuminate\Support\Facades\Validator::class,
    'View'      => Illuminate\Support\Facades\View::class,
    //.....
    'YourAlias'      => \Buzz\LaravelSettingFacade::class,
],
```

#### Tắt tự động lưu

Mặc định package sẽ chỉ lưu khi kết thúc request và settings có thay đổi bất kì, nếu bạn muốn tắt chức năng này thì sửa `'auto_save' => true,` và sau đó sử dụng `Setting::save()` (hoặc `YourAlias::save()` nếu bạn đã đổi alias).
***Lưu ý: tính năng tự động lưu sẽ không hoạt động nếu bạn dùng `exit` hoặc `die` function



### Các hàm hỗ trợ

```php
Setting::clean();//xóa tất cả settings
Setting::save($force = false);//Lưu tất cả các thay đổi
Setting::all();//Lấy ra tất cả settings
Setting::has($key);//kiểm tra sự tồn tại của setting theo key
Setting::get($key, $default = false, $system = false);//lấy giá trị theo key
Setting::setData($data);//ghi đè tất cả settings
Setting::add($key, $value);//thêm mới một setting
Setting::add([
    ['name' => 'name1', 'value' => 'value1'],
    ['name' => 'name2', 'value' => 'value2'],
    ['name' => 'name3', 'value' => 'value3'],
]);//thêm mới nhiều settings
Setting::remove($key);//xóa một setting theo key
Setting::remove([
    'name1',
    'name2',
    'name3',
]);//xóa nhiều settings theo key
Setting::set($key, $default);//cập nhật giá trị mới cho setting theo key
Setting::set([
    ['name' => 'name1', 'value' => 'value1'],
    ['name' => 'name2', 'value' => 'value2'],
    ['name' => 'name3', 'value' => 'value3'],
]);//cập nhật giá trị mới cho nhiều settings theo key
Setting::sync();//thêm tất cả config của app vào Setting
Setting::sync('mail');//thêm config của app vào Setting theo key
Setting::sync([
    'key1',
    'key2',
    'key3',
]);//Thêm nhiều config của app vào Setting theo key
```

> Package sử dụng các [array helpers](http://laravel.com/docs/5.1/helpers#arrays) của laravel, để biết cách sử dụng `$key` rõ hơn vui lòng đọc thêm tại [Laravel Helper Functions](http://laravel.com/docs/5.1/helpers#arrays).

## Contribute

https://github.com/thinhbuzz/laravel-setting/pulls