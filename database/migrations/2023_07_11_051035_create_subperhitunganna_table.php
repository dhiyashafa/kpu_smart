<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subperhitungans_na', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perhitungan_id')->constrained()->onDelete('cascade');
            $table->foreignId('kriterias_id')->constrained()->onDelete('cascade');
            // $table->text('type');
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
        Schema::dropIfExists('subperhitungans');
    }
};