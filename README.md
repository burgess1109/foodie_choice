# Hello 吃貨  !

[![Build Status](https://travis-ci.org/burgess1109/foodie_choice.svg?branch=master)](https://travis-ci.org/burgess1109/foodie_choice)

參考了一些 Unit Test 及 Design Patterns 的文章，順便也玩看看 MongoDB 和 VueJS，於是就寫了這支援跨資料庫(Mysql,MongoDB)，隨機選擇餐廳的小 project ，順便記錄一下心得。

介面 

![圖示說明](https://github.com/burgess1109/foodie_choice/blob/master/demo1.png) 

![圖示說明](https://github.com/burgess1109/foodie_choice/blob/master/demo2.png) 


# 安裝步驟

* 下載
```
git clone https://github.com/burgess1109/foodie_choice.git
```

* 複製 .env.example

```
cp .env.example .env
```

* 設定 .env 的 DB 參數，DB 可選擇使用 mysql or mongo，選擇一種 DB 設定後，請把另一種註解掉

```
# mysql setting
DB_CONNECTION=mysql
DB_HOST=mariaDB
DB_PORT=3306
DB_DATABASE=project_foodie
DB_USERNAME=root
DB_PASSWORD=your_password

# mongo setting
#DB_CONNECTION=mongodb
#DB_HOST=mongoDB
#DB_PORT=27017
#DB_DATABASE=project_foodie
#DB_USERNAME=root
#DB_PASSWORD=
```

* 建立 images & containers
```
docker-compose up -d
```

* 初始化設定
```
make init
```

* 開啟網頁 http://localhost:8080 or http://localhost:8080/facade 就可看到畫面囉！

# 檢查及測試

## PSR-12 檢查

使用 [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) 檢查程式碼是否符合 PSR-12 標準，設定檔請查看 [phpcs.xml](./phpcs.xml)


執行 PSR-12 檢查
 ```php
docker-compose exec nginx-php ./vendor/bin/phpcs ./
 ```

## 單元測試

執行 phpunit

 ```php
docker-compose exec nginx-php ./vendor/bin/phpunit
 ```

在 tests 路徑下有寫了一些 Test Code，PHP 的 [mockery](https://github.com/mockery/mockery) 跟 [faker](https://github.com/fzaninotto/Faker) 兩個 packages 很實用，讓撰寫測試替身時省了不少功夫， Laravel 預設都有載入。

# Dependency Injection & Container

## Dependency Injection (依賴注入)

依賴注入是一種移除 hard-coded 類別依賴的方法，最常見的方式是在物件的 `__construct` 進行其他依賴物件的注入。

 ```php
class MyClass
{
     private $aService;
     
     private $bService;
          
     public function __construct(AInterface $AService, BInterface $BService)
     {
         $this->aService = $AService;
         $this->bService = $BService;
         
         ...
     }
}
 ```
 
傳統的 hard-coded 類別依賴可能會在 `__construct` 裡寫成 `$this->aService = new AService();` ，這種寫法有幾個缺點 :
 
* 撰寫測試時不易 Mock FoodieService
* 抽換成其他 Service 可能有風險

而依賴注入可在撰寫測試時直接 Mock 掉依賴類別，且可迅速抽換成其他符合 interface 的 Service，
Controller 內容不必更動, 專注在 Service 邏輯即可。

## Dependency Injection Container
前面說的 Dependency Injection 有個缺點，假設我的物件需要使用到許多相依類別，程式會變成下列樣子:
 
 ```php
 class MyClass
{
    private $aService;
    
    private $bService;
    
    private $cRepository;
    
    private $dRepository;
    
    private $fClass;
         
    public function __construct(AInterface $AService, BInterface $BService, CInterface $CRepository, DRepository $DRepository, FInterface $FClass)
    {
        $this->aService = $AService;
        $this->bService = $BService;
        $this->cRepository = $CRepository;
        $this->dRepository = $DRepository;
        $this->fClass = $FClass;
        
        ...
    }
}
 ```

如果要執行 `$myClass->someMethod()`，實例化 MyClass 之前必須把相依的類別一一實例化，程式看起來會變得複雜

```php

$aService = new AService();
$bService = new BService();
$cRepository = new CRepository($aService, $bService);
...

$myClass = new MyClass($aService, $bService, ...)
$myClass->someMethod()
...

```
 
這時如果有一個 container，可以來幫助我們管理依賴關係，當我要調用某個類別，去找這 container 就好，是不是比較看起來會比較單純？

PHP 常見的依賴 container 管理工具有 [Pimple](https://github.com/silexphp/Pimple)，定義好 service 後，只要透過 container 取得 service，這種 container 又可稱作 DI Container or IoC Container。  


首先要在 container 中去定義相關類別實例化方法
```php
$container = new Pimple();

$container[AService::class] = function($c) {
	return new AService();
};

$container[BService::class] = function($c) {
	return new BService();
};

$container[CRepository::class] = function($c) {
	return new CRepository($c[AService::class], $c[BService::class]);
};
...

$container[MyClass::class] = function($c) {
	return new MyClass($c[AService::class], $c[BService::class], ....);
};
```

再把上面的 code 整理一下，這些相關類別實例化方法可以整合成一個 Provider 類別

```php
use Pimple\Container;

class MyClassProvider implements Pimple\ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[AService::class] = function($c) {
        	return new AService();
        };
        
        $container[BService::class] = function($c) {
        	return new BService();
        };
        
        $container[CRepository::class] = function($c) {
        	return new CRepository($c[AService::class], $c[BService::class]);
        };
        ...
        
        $container[MyClass::class] = function($c) {
        	return new MyClass($c[AService::class], $c[BService::class], ....);
        };
    }
}
```

container 去註冊 provider，即完成定義相關類別實例化方法
```php
$container = new Pimple();
$container->register(new MyClassProvider());
```

之後要調用 MyClass 只需要從 $container 取得即可：

```php
$myClass = $container[MyClass::class];
$myClass->someMethod();
```

## Laravel Architecture Concepts

Laravel Architecture 包含了 [Service Container](https://laravel.com/docs/6.x/container)(IoC Container)、[Service Providers](https://laravel.com/docs/6.x/providers)、[facade](https://laravel.com/docs/6.x/facades) 等等。

上面提過 Dependency Injection Container，由 Container 去管理依賴，Container 逼需一一定義注入的依賴，
Laravel Service Container 是更神奇了，binding 類別的依賴對象會被 **reflect** 出來並且實例化它，也就是說那些注入的依賴類別會自行實例化。
依剛剛的例子，定義類別只要寫成：

```php
use Illuminate\Container\Container;
...

$container = Container::getInstance();
$myClass = $container->make(MyClass::class);
```

MyClass 其他相依的類別(AService, BService, ...)，Laravel Service Container 會自行幫我們實例化，有沒有很神奇啊！

關於 Laravel Architecture Concepts 有興趣可以參考官網或是我寫的 [Laravel Architecture Concepts 心得](https://gist.github.com/burgess1109/6d05b88d28d537087cc66a463c2be32a)

另外，Laravel 很多的核心元件都可透過 facades 取得該類別，當然也可以透過 Service Container or help function 方式取得，舉例來說，有三種方式可以取得 config 的 database.default 設定 : 
```php
// By Service Container
use Illuminate\Foundation\Application;

$config = Application::getInstance()->get('config');
$dbConnect = $config->get('database.default');
     
// By Facade
use Illuminate\Support\Facades\Config;

$dbConnect = Config::get('database.default');
  
// By Helper Function (官網)
$dbConnect = config('database.default');
```

helper function 其實就是去操作 container 中的 `'config'` 類別，做法跟第一種一樣。


# 後端架構

主要嘗試使用單純 Dependency Injection 的 FoodieController 
以及運用 Service Container & Facades 的 RestaurantController 的差異。

預設 http://localhost:8080 會通向 FoodieController ，而 http://localhost:8080/facade 會通向 RestaurantController


## RESTful Resource Controllers

本例使用 [Laravel RESTful Resource Controllers](https://laravel.com/docs/6.x/controllers#resource-controllers)，API 格式文件可參考 openapi.yaml。

依 laravel 官方文件，RESTful 的 CRUD request 會自行對應到 Controller 中不同的 action。

## Service 和  Repository

後端 Controller 和 Model 部分就參考[點燈坊的 Laravel 中大型專案架構](https://www.muzilong.cn/storage/html/2/oomusou.io/laravel/laravel-architecture/index.html)，
分出 Service 及 Repository，避免過於 Controller 和 Model 肥大，其實本例很單純可以不需要拆分，但...還是拆分出來了

## Model

Model 有分 Mysql 跟 Mongo，Mysql 繼承原本 [Laravel Eloquent 的 Model](https://github.com/illuminate/database/tree/master/Eloquent)，
Mongo 則要繼承 [Jenssegers Mongodb 的 Model](https://github.com/jenssegers/laravel-mongodb)。

## Foodie

### FoodieController

本例的 [FoodieController](./app/Http/Controllers/FoodieController.php) 使用 Dependency Injection，將 Service 注入 Controller。


### RepositoryFactory & FoodieRepository

針對 DB 切換的狀況，簡單使用 Simple Factory Pattern，
[RepositoryFactory](./app/Services/Foodie/RepositoryFactory.php) 依據 config 的 `database.default` 設定值，
切換不同的 model ，並注入 Repository。這樣做的好處是未來如果要加入新的DB連線方式，假設是MSSQL，
只要在 models 下新增 MSSQL 資料夾跟相關 model 檔案，再去修改 RestaurantFactory 條件即可，
其他 Controller、Service、Repository 完全不用修改，原有 Model 也不會影響到，降低了程式的耦合性。

## Restaurant

### RestaurantController

本例將 [RestaurantController](./app/Http/Controllers/RestaurantController.php) 所相依的類別綁定到 [RestaurantServiceProvider](./app/Providers/RestaurantServiceProvider.php)，
接著在 config/app.php 的 'providers' index 中新增 `App\Providers\RestaurantServiceProvider::class`，
之後就可以透過 help function 去取得相應類別了

```php
$menuService = app(MenuService::class);
$this->repository = app('restaurant.repository');
$this->model = app('restaurant.model');
```
 
另外一提 provider 在使用 bind 或 singleton 的差異是是否使用單例模式，單例模式是一個類別只有一個實例，並提供全域存取此單一實例，適用唯一性很重要的情境，如計時器、序號產生器或是對資料操作的時候。 Laravel 的 Cache、DB、Log、Redis、Router 都是使用 singleton。


### Restaurant Service
本例將 [Restaurant 的 Service](./app/Services/Restaurant/Service.php) 加入 Facades，
方法很簡單，新增一個 [Restaurant Facade](./app/Facades/Restaurant.php)，注意 getFacadeAccessor method 必須 return 對應 RestaurantServiceProvider 的 binding name :

 ```php
    protected static function getFacadeAccessor()
    {
        // 對應 RestaurantServiceProvider bind name
        return 'restaurant';
    }
 ```

接著去 config/app.php 的 'aliases' index 中新增 `'Restaurant'  => App\Facades\Restaurant::class`
 
完成 facade 設置，之後在任何地方使用靜態方法操作 Restaurant 的 Service : 

 ```php
    Restaurant::getData();
    Restaurant::getDataById($id);
    Restaurant::updateData($data, $id);
    Restaurant::createData($data);
    Restaurant::deleteData($id);
 ``` 
 
# 前端架構

前端架構就是...沒架構，第一次碰前端 framework，前端也不是我想要 focus 的重點...所以就只是很 ugly 的寫完 app.js 跟嘗試前端 two-way data bindings 的奧妙，也沒甚麼值得說嘴的。

前端的 ajax requests 是使用 Axios 套件。
