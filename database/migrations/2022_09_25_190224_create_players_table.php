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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string("name", 50);
            $table->integer("number")->nullable();
            // $table->foreignId('team_id')->constrained('teams', 'id')->onDelete('set null');
            // $table->foreign('team_id')
            //         ->references('id')
            //         ->on('teams')
            //         ->onDelete('set null')
            //         ->nullable();

            $table->timestamps(); 
        });

        Schema::table('players', function (Blueprint $table) {
            $table->foreignId('belongs_to')->nullable()->constrained('teams', 'id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
};
