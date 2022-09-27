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
        Schema::create('team_matches', function (Blueprint $table) {
            $table->id();
            $table->date("match_date");
            $table->time("start_at");
            $table->time("end_at");
            $table->foreignId("team_h")->constrained("teams", "id")->onDelete('restrict');
            $table->foreignId("team_v")->constrained("teams", "id")->onDelete('restrict');
            $table->integer("goals_h")->default(0);
            $table->integer("goals_v")->default(0);
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
        Schema::dropIfExists('team_matches');
    }
};
