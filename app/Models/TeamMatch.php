<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMatch extends Model
{
    // use HasFactory;
    protected $table = "team_matches";

    protected $fillable = ["match_date", "start_at", "end_at", "team_h", "team_v", "goals_h", "goals_v"];
}
