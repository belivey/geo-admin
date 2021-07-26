<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrains('countries')->nullable();
            $table->unsignedBigInteger('osm_id');
            $table->string('title');
            $table->geometry('boundary')->nullable();
            $table->json('props')->nullable();
            $table->timestamps();

            $table->index('country_id');
            $table->spatialIndex('boundary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('counties');
    }
}