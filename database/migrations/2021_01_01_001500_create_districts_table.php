<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region')->constrains('regions')->nullable();
            $table->enum('type', ['municipal', 'urban'])->nullable();
            $table->unsignedBigInteger('osm_id')->nullable();
            $table->string('title');
            $table->geometry('boundary');
            $table->json('props')->nullable();
            $table->timestamps();

            $table->index('region_id');
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
        Schema::dropIfExists('districts');
    }
}