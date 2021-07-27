<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrains('regions')->nullable();
            $table->foreignId('district_union_id')->constrains('district_unions')->nullable();
            $table->foreignId('district_id')->constrains('districts')->nullable();
            $table->enum('type', ['rural', 'urban', 'federal'])->nullable();
            $table->unsignedBigInteger('osm_id')->nullable();
            $table->string('title');
            $table->geometry('boundary');
            $table->json('props')->nullable();
            $table->timestamps();

            $table->index('region_id');
            $table->index('district_union_id');
            $table->index('district_id');
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
        Schema::dropIfExists('sub_districts');
    }
}