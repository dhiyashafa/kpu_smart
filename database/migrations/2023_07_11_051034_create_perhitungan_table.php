<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerhitunganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perhitungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternatifs_id')->constrained()->onDelete('cascade');
            // $table->foreignId('kriterias_id')->constrained();
            $table->double('hasil');
            $table->softDeletes();
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
        Schema::dropIfExists('perhitungans');
    }
}
