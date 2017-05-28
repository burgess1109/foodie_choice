<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Restaurant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $db_connect= Config::get('database.default');
        switch($db_connect)
        {
            case 'mongodb':
                if (!Schema::hasCollection('restaurant')) {
                    Schema::create('restaurant', function ($collection) {
                        $collection->unique('_id')->index();
                        $collection->index('name');
                    });
                }
                break;
            default:
                if (!Schema::hasTable('restaurant')) {
                    Schema::create('restaurant', function (Blueprint $table) {
                        $table->increments('id')->unique();
                        $table->string('name')->index();
                        $table->string('address')->nullable();
                        $table->string('tel',12)->nullable();
                        $table->string('opentime')->nullable();
                        $table->string('status',10)->nullable()->default('enabled');
                    });
                }
                break;
        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('restaurant');
    }
}
