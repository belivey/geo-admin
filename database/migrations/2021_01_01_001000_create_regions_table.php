<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrains('countries')->nullable();
            $table->foreignId('county_id')->constrains('counties')->nullable();
            $table->string('title');
            $table->geometry('boundary')->nullable();
            $table->json('props')->nullable();
            $table->timestamps();

            $table->index('country_id');
            $table->index('county_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
}