# Hello 吃貨  !

[![Build Status](https://travis-ci.org/burgess1109/foodie_choice.svg?branch=master)](https://travis-ci.org/burgess1109/foodie_choice)

最近看了一些 Unit Test 及 Design Patterns 的文章，順便也想玩看看 MongoDB 和 VueJS，於是就寫了這支援跨資料庫(Mysql,MongoDB)，隨機選擇餐廳的小project ，順便記錄一下學習的心得，希望多多指教囉 ! 

介面 

![圖示說明](https://github.com/burgess1109/foodie_choice/blob/master/demo1.png) 

![圖示說明](https://github.com/burgess1109/foodie_choice/blob/master/demo2.png) 


# 下載 & 安裝方式

注意事項

* 請先確認有無安裝 php_mongodb extension

    Linux 可使用 pecl 安裝

    http://php.net/manual/en/mongodb.installation.pecl.php

    Windows 可直接下載 .dll 檔

    https://pecl.php.net/package/mongodb

* PHP 版本需求
    * Laravel >= 5.5
    * PHP >= 7.1
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
    * Tokenizer PHP Extension
    * XML PHP Extension
    
* 其他需求    

    * NodeJS 及 npm ( https://nodejs.org/en )
    * Composer (https://getcomposer.org/download)

下載程式

```php
git clone https://github.com/burgess1109/foodie_choice.git
```

初始化 composer & npm ：

```php
composer install

npm install
```

設定 .env 檔 ：

```php
cp .env.example .env

```
* note : 若有 Mysql 與 MongoDB 切換需求，請將 .env 中 DB 相關環境參數註解掉，改由 database.php 統一設定。

    ```php
    #DB_CONNECTION=mysql
    #DB_HOST=localhost
    #DB_PORT=3306
    #DB_DATABASE=project_2018
    #DB_USERNAME=root
    #DB_PASSWORD=
    ```

編輯您的 DB 主機 IP、port、資料庫、帳密 (config/database.php)。

 ```php
 #預設為mysql
 'default' => env('DB_CONNECTION', 'mysql'),
 
 'mysql' => [
             'driver' => 'mysql',
             'host' => env('DB_HOST', '127.0.0.1'),
             'port' => env('DB_PORT', '3306'),
             'database' => env('DB_DATABASE', 'project_2018'),
             'username' => env('DB_USERNAME', 'root'),
             'password' => env('DB_PASSWORD', ''),
         ],
  
#增加mongodb
  'mongodb' => [
              'driver'   => 'mongodb',
              'host'     => env('DB_HOST', '127.0.0.1'),
              'port'     => env('DB_PORT', 27017),
              'database' => env('DB_DATABASE','project_2018'),
              'username' => env('DB_USERNAME','root'),
              'password' => env('DB_PASSWORD',''),
              'options'  => [
                  'database' => 'admin' // sets the authentication database required by mongo 3
              ]
          ],       
 
 ```
* note : 若修改config內容，請記得清掉 Laravel config cache，否則仍撈取舊的設定。

    ```php
    php artisan config:clear
    ```

產生 APP KEY
 ```php
php artisan key:generate
 ```

創建 Database
 ```php
mysql -e 'create database project_2018;'
 ```

創建 Table Restaurant 及 初始資料

 ```php
php artisan migrate:refresh
php artisan db:seed --class=RestaurantTableSeeder
 ```

執行 npm run dev 

 ```php
npm run dev 
 ```
 
接著...開啟網頁就能看到畫面囉~ 
(web server root 路徑需指到本專案 public 下)。

# 後端架構

雖然這只是小示例，但是...我就想殺雞用牛刀...

## RESTful Resource Controllers

Controller 使用 Laravel RESTful Resource Controllers 設計， API 格式文件可參考 openapi.yaml。

依 laravel 官方文件，本例執行下列指令即產生符合 RESTful 的 CRUD。

 ```php
php artisan make:controller FoodieController
 ```

並在 route/web.php 綁定即可
 ```php
 Route::resource('foodie', 'FoodieController');
 ```

## Service 和  Repository

後端 Controller 和 Model 部分就參考點燈坊的中大型專案架構，分出 Service 及 Repository
   
http://oomusou.io/laravel/architecture


## Dependency Injection (依賴注入)

依賴注入是一種移除 hard-coded 類別依賴的方式，最常見的方式是在物件的 __construct 進行其他依賴物件注入。

本專案預設 localhost 是使用依賴注入的 controller(FoodieController) 。

FoodieController 需使用到 FoodieService 及 MenuService 物件，因此在 __construct 注入該物件 。

 ```php
 class FoodieController extends BaseController
{
    /** @var object FoodieService */
    protected $foodieService;
    
    /** @var object MenuService */
    protected $menuService;
         
    /**
     * FoodieController constructor.
     *
     * @param FoodieInterface $foodieService
     * @param MenuInterface $menuService
     */
    public function __construct(FoodieInterface $foodieService, MenuInterface $menuService)
    {
        $this->foodieService = $foodieService;
        $this->menuService = $menuService;
    }
}
 ```
 
傳統的 hard-coded 類別依賴可能會在 __construct 裡寫成 $this->foodieService = new FoodieService(); ，這種寫法有幾個缺點 :
 
* 撰寫測試時不易 Mock FoodieService
* 抽換成其他 Service 可能有風險

而依賴注入可在撰寫測試時直接 Mock 掉依賴類別，且可迅速抽換成其他符合 interface 的 Service，Controller 內容不必更動, 專注在 Service 邏輯即可。

本案例子將 Service 注入 Controller，Model 注入 Repository。( P.S.寫習慣 Active Record ORM 的人往往對 Model 直接實例化並進行操作，可能對 Repository 會感到陌生，可以去接觸看看 Data Mapper ORM ，例如 Doctrine2 : https://www.doctrine-project.org ，也許更能體會 Repository )。

因為有 Mongo 跟 Mysql，Mysql 繼承原本 Laravel Eloquent 的 Model，Mongo 則要繼承 Jenssegers Mongodb 的 Model，Jenssegers Mongodb使用方法詳 https://github.com/jenssegers/laravel-mongodb 。

針對DB切換的狀況，只簡單使用 Simple Factory Pattern，FoodieFactory 依據 DB_CONNECT 預設值，return Service 需引用的 Repository。

這樣做的好處是未來如果要加入新的DB連線方式，假設是MSSQL，只要在 models 下新增 MSSQL 資料夾跟相關 model 檔案，再去修改 RestaurantFactory 條件即可，其他 Controller,Service,Repository 完全不用修改，原有 Model 也不會影響到，降低了程式的相依性。
  
另可將 FoodieFactory 改用 LUT 表示並移至設定檔中，將可達到工廠模式的開放封閉(http://oomusou.io/tdd/tdd-factory-ocp/) 。
 
此外，欲在 Controller 使用依賴注入, 記得在 AppServiceProvider register 處進行 bind 。
 ```php
    public function register()
    {
        $this->app->bind('App\Services\Foodie\FoodieInterface', 'App\Services\Foodie\FoodieService');
        $this->app->bind('App\Services\Menu\MenuInterface', 'App\Services\Menu\MenuService');
    }
 ```

## IoC Container  和  Facades

這其實是兩個不一樣的 Pattern，但 Laravel 把兩者整合在一起了，所以寫在同一個 section 。

本專案預設 localhost/ioc 使用 IoC Container 及 Facades 的 controller(RestaurantController) 。

前面說的 Dependency Injection 有個缺點，假設我的物件需要使用到許多相依類別，程式會變成下列樣子:
 
 ```php
 class MyClass
{
    protected $AaaService;
    
    protected $BbbService;
    
    protected $CccRepository;
    
    protected $DddRepository;
    
    protected $FffClass;
         
    public function __construct(AaaInterface $AaaService, BbbInterface $BbbService, CssInterface $CssService, $DddRepository $DddRepository, FffInterface $FffClass)
    {
        $this->aaaService = $AaaService;
        $this->bbbService = $BbbService;
        $this->cccService = $CccRepository;
        $this->dddService = $DddRepository;
        $this->fffClass = $FffClass;
    }
}
 ```

可以看出 __construct 會變得很長，這時候需要 IoC Container ( or DI container) pattern 來幫助我們管理依賴。

Container 介紹及使用方式可以先參考 [Pimple](https://github.com/silexphp/Pimple)，而 Facade 可以去網路搜尋 Facade Pattern，對 Pimple 及 Facade Pattern 有概念後會更容易理解 Laravel IoC Container 。

Laravel Facades 原理請參考 https://laravel-china.org/docs/laravel/5.5/facades/1291 ，接下來僅說明使用及實作方式。

簡單舉得例子，laravel config helper function 大家都會使用，其實 config 有三種取得方法 : 
```php
// IoC container 取法
$config = $this->app->get('config');
$dbConnect = $config->get('database.default');
     
// Laravel facade 取法
$dbConnect = Config::get('database.default');
  
// laravel helper 取法(官網)
$dbConnect = config('database.default');
```

上述例子欲取得 config 的 database.default 設定，單純使用 IoC container，取得 container 中註冊為 'config' 的類別並使用該類別 get 方法需要兩行程式碼。若加入 facade pattern 僅需一行，使用起來較為方便。

而 Laravel 5 之後更進一步提供全域的 config helper function 去操作 container 中的 'config' 類別，查看 Laravel config 及 app helper function，其實就是去操作 container : 

 ```php
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }
   
        if (is_array($key)) {
            return app('config')->set($key);
        }
   
        return app('config')->get($key, $default);
    }
       
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
 ```


本例欲實作 Laravel IoC Container 及 Facades ，因此先在 RestaurantServiceProvider 管理相依套件，註冊了 restaurant、MenuService::class、restaurant.repository、restaurant.model

 ```php
    public function register()
    {
        // 設定相依
        $this->app->bind('restaurant', function () {
            return new RestaurantService;
        });
    
        $this->app->bind(MenuService::class, function () {
            return new MenuService;
        });
    
        $this->app->bind('restaurant.repository', function () {
            return new RestaurantRepository;
        });
    
        $this->app->singleton('restaurant.model', function () {
            $dbConnect = config('database.default');
            return ModelFactory::create($dbConnect);
        });
    }

 ```
 
bind 跟 singleton 差異是是否使用單例模式，單例模式是一個類別只有一個實例，並提供全域存取此單一實例，適用唯一性很重要的情境，如計時器、序號產生器或是對資料操作的時候。 Laravel 的 Cache、DB、Log、Redis、Router 都是使用 singleton ，細節可自行上網搜尋

設定完 RestaurantServiceProvider 後，在 app 下加入 Facades/Restaurant.php，建立 getFacadeAccessor method ， return 對應 RestaurantServiceProvider bind name :

 ```php
    protected static function getFacadeAccessor()
    {
        // 對應 RestaurantServiceProvider bind name
        return 'restaurant';
    }
 ```

接著再去修改 config/app.php
 
  ```php
  // 'providers' 加入
   App\Providers\RestaurantServiceProvider::class
   
   // 'aliases' 加入
   'Restaurant'  => App\Facades\Restaurant::class
   ```
 
這樣即完成 facade 設置，之後在任何地方使用靜態方法操作 RestaurantService 的 method : 

 ```php
    Restaurant::getData();
    Restaurant::getDataById($id);
    Restaurant::updateData($request, $id);
    Restaurant::createData($request);
    Restaurant::deleteData($id);
 ```

由於 RestaurantServiceProvider 的其他類別沒有進行 facade 註冊，需用 app helper function 取得 container 進行操作，如下 : 

 ```php
    app(MenuService::class)->getData();
    app('restaurant.repository')->getData();
    app('restaurant.model')->get();
    
    //也可以這樣取
    App::get(MenuService::class)->getData();
    App::get('restaurant.repository')->getData();
    App::get('restaurant.model')->get();
    
 ```

本例 RestaurantController 、 RestaurantService 、 RestaurantRepository 皆未再使用依賴注入，欲使用類別時就從 container 取得，而這些類別統一在 provider 進行註冊，當然可以依需求混用 IoC Container 及依賴注入 。

### Deferred Providers

補充說明，本例的 RestaurantServiceProvider 在 Laravel 啟動就會載入，若想在該 service container 實際被使用才做 register 與 binding ，可以使用 deferred provider 。

設定方式可參考 [Laravel Service Providers](https://laravel.com/docs/5.5/providers)，service provider 需額外設定 protected $defer 跟 public function provides() 。

若使用 deferred provider ，用 app help function 取得方式不變，但用 APP facade 就要改成 make :

```php
    app(MenuService::class)->getData();
    app('restaurant.repository')->getData();
    app('restaurant.model')->get();
    
    //改成這樣取
    App::make(MenuService::class)->getData();
    App::make('restaurant.repository')->getData();
    App::make('restaurant.model')->get();
    
 ```
 
# 前端架構

前端架構就是...沒架構，第一次碰前端 framework，前端也不是我想要 focus 的重點...所以就只是很 ugly 的寫完 app.js 跟嘗試前端 two-way data bindings 的奧妙，也沒甚麼值得說嘴的。

前端的 ajax requests 是使用 Axios 套件
 

# 檢查及測試

## PSR-2

本專案有安裝 [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) ，檢查程式碼是否符合 PSR-2 標準，請查看 phpcs.xml

 ```php
 #全部檢查
vendor/bin/phpcs

#檢查某一支
vendor/bin/phpcs tests/Unit/FoodieTest.php
 ```

## 單元測試

在 tests/Unit 路徑下有寫了一些 Test Code 涵蓋一些驗收測試及單元測試，當然可以依需求自行擴充

 ```php
 #全部測試
vendor/bin/phpunit

#測試某一支
vendor/bin/phpunit tests/Unit/FoodieTest.php
 ```

接觸了單元測試後，自然就會對 OOP 及 SOLID 的觀念有更深一層的體會，並開始痛恨 hard-coded 類別依賴 XD

PHP 的 mockery 跟 faker 兩個 packages 很實用，讓撰寫測試替身時省了不少功夫， Laravel 的 composer.json 預設就有載入

另外 Laravel 的 Facade 很強大，可在 Test 直接引用並自行進行 mock :

 ```php
 use App\Facades\Restaurant;
 
 Restaurant::shouldReceive('getData')->andReturn('your data');
 
 // Then you can do some testing ...
  ```
  
有興趣可以查看 Illuminate\Support\Facades\Facade


# 結語

查了許多網路上的資料，也有請教一些前輩，網路上大部分教學都只是點到為止，於是想說整合消化一下，弄個小功能來玩玩，才能更熟悉相關的 know how。未來有新的想法將會持續做更正，也請各位大大們不吝賜教。


















