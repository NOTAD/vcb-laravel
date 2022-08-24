<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->id();
            //basic info
            $table->string('logo');
            $table->string('favicon');
            $table->string('name');
            $table->string('address');
            $table->string('phone', 15);
            $table->string('hotline', 15);
            $table->string('email', 50);
            $table->string('color', 50);

            // seo
            $table->string('title');
            $table->string('keyword');
            $table->string('description');
            $table->string('thumbnail');

            // images
            $table->json('slider')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();

            // 3th party
            $table->string('gg_analytic_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
