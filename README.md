# Hello 吃貨 !
最近看了一些 Unit Test 及 Design Patterns 的文章，順便想玩看看 MongoDB 和 VueJS，於是就寫了這支援跨資料庫的隨機選擇餐廳的小project ，順便記錄一下學習的心得，希望大大們多多指教囉! 

介面

![圖示說明](https://github.com/burgess1109/foodie_choice/blob/master/demo1.jpg) 

![圖示說明](https://github.com/burgess1109/foodie_choice/blob/master/demo2jpg) 


# 下載 & 安裝方式

注意事項

* 若要使用mongodb，請先確認有無安裝 php_mongodb extension

    Linux 可使用 pecl 安裝

    http://php.net/manual/en/mongodb.installation.pecl.php

    Windows 可直接下載 .dll 檔

    https://pecl.php.net/package/mongodb

* Laravel 5.4 需求

    https://laravel.com/docs/5.4
    
    * PHP >= 5.6.4
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
    * Tokenizer PHP Extension
    * XML PHP Extension
    * NodeJS 及 npm
    * Composer

下載程式

```php
git clone https://github.com/burgess1109/foodie_choice.git
```

初始化 composer & npm ：

```php
composer install

npm run dev
```

設定 .env 檔 ：

```php
cp .env.example .env

```
* note : 若有 Mysql 與 MongoDB 切換需求，請將DB相關環境參數請註解掉，
改由 database.php 統一設定

    ```php
    #DB_CONNECTION=mysql
    #DB_HOST=localhost
    #DB_PORT=3306
    #DB_DATABASE=project_2017
    #DB_USERNAME=root
    #DB_PASSWORD=
    ```

編輯您的 DB 主機 IP、port、資料庫、帳密 (config/database.php)

 ```php
 #預設為mysql
 'default' => env('DB_CONNECTION', 'mysql'),
 
 'mysql' => [
             'driver' => 'mysql',
             'host' => env('DB_HOST', '127.0.0.1'),
             'port' => env('DB_PORT', '3306'),
             'database' => env('DB_DATABASE', 'project_2017'),
             'username' => env('DB_USERNAME', 'root'),
             'password' => env('DB_PASSWORD', ''),
         ],
  
#增加mongodb
  'mongodb' => [
              'driver'   => 'mongodb',
              'host'     => env('DB_HOST', '127.0.0.1'),
              'port'     => env('DB_PORT', 27017),
              'database' => env('DB_DATABASE','project_2017'),
              'username' => env('DB_USERNAME','root'),
              'password' => env('DB_PASSWORD',''),
              'options'  => [
                  'database' => 'admin' // sets the authentication database required by mongo 3
              ]
          ],       
 
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
 

# 單元測試(PHPUnit)

相關知識可以參考下列網址

* Laravel Testing : 
https://laravel.com/docs/5.4/testing

* PHPUnit : 
https://phpunit.de/manual/current/zh_cn/writing-tests-for-phpunit.html

* Mockery : 
http://docs.mockery.io/en/latest

* fzaninotto/Faker : 
https://github.com/fzaninotto/Faker

* 有效的單元測試 : 
https://speakerdeck.com/jaceju/effective-unit-testing

* 解決相依物件而無法隔離測試的方法 :
http://oomusou.io/tdd/tdd-isolated-test

在 tests/Unit 下有寫了一些 Test Code

涵蓋一些驗收測試及單元測試，當然可以依需求自行擴充

嘗試了 PHP Mockery 及 Faker package

* FoodieTest.php : 驗收測試, 進行 GET, POST, PATCH, DELETE 
及 Exception 測試

* Restaurant/FoodieControllerTest.php : 針對 FoodieController 測試

* Restaurant/RestaurantServiceTest.php : 針對 RestaurantService 測試

* Restaurant/RestaurantRepositoryTest.php : 針對 RestaurantRepository 測試


 ```php
 #全部測試
vendor/bin/phpunit

#測試某一支
vendor/bin/phpunit tests/Unit/FoodieTest.php
 ```

接觸了單元測試後，自然就會對OOP有更深一層的體會，尤其是 SOLID 及 IoC/DI 的觀念

PHP 的 mockery 跟 faker 兩個 packages 實在太強大，讓撰寫測試替身時省了不少功夫，非常推薦使用(P.S. Laravel 的 composer.json 預設有載入)

除了程式架構外，如何進行"有效"的單元測試，測試的對象是誰？替身是誰？測試程式的涵蓋率重要嗎？測試程式的可讀性、可維護性、可靠性...等等問題。我想專案越龐大，這些問題也會越頭大......


# 後端架構

後端 Controller 和 Model 部分就參考點燈坊的中大型專案架構

http://oomusou.io/laravel/laravel-architecture

雖然這只是小範例，但是...我就想殺雞用牛刀...

總之，Controller 用 Laravel RESTful Resource Controllers，另在 app 目錄開了 Models、Repositories、Service，Models 下又增加了 Mongo 跟 Mysql，Service 注入 Controller，Model 注入 Repository。(P.S.寫習慣 Active Record 的 ORM 的人可能對 Repository 會感到陌生，可以去接觸看看 Data Mapper ORM ，例如 Doctrine2 : http://docs.doctrine-project.org/en/latest )

因為有 Mongo 跟 Mysql，Mysql 繼承原本 Laravel Eloquent 的 Model，Mongo 則要繼承 Jenssegers Mongodb 的 Model ，詳 : 

https://github.com/jenssegers/laravel-mongodb

於是搜尋了一下腦海中所學不多的 Design Patterns ，似乎 Simple Factory Pattern 比較符合需求，RestaurantFactory 依據 DB_CONNECT 預設值，return RestaurantService 需引用的 Repository。另外，如果要轉換 DB，可使用 Service 的 method setDBConnect

 ```php
 $restaurantService = new RestaurantService();
 $restaurantService->setDBConnect('mongodb');
 #取得 mongodb restaurant 所有資料
 $restaurantService->getData();
 ```

# 前端架構

前端架構就是...沒架構，第一次碰前端 framework，前端也不是我想要 focus 的重點...所以就只是很 ugly 的寫完 app.js 跟嘗試一些 VueJS 渲染，也沒甚麼值得說嘴的。

Oh ~ ajax requests 是使用 Axios套件























