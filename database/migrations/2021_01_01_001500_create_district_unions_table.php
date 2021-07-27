<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictUnionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_unions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrains('regions')->nullable();
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
        Schema::dropIfExists('district_unions');
    }
}