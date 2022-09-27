<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TeamMatch;
use App\Models\Team;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $allMatches = TeamMatch::all();

            $classification = [];

            foreach ($allMatches as $match) {
                $classification[$match->team_h] 
                    = isset($classification[$match->team_h]) 
                    ? $classification[$match->team_h] 
                    : ["Team" => Team::find($match->team_h)->name];

                $classification[$match->team_v] 
                    = isset($classification[$match->team_v]) 
                    ? $classification[$match->team_v] 
                    : ["Team" => Team::find($match->team_v)->name];

                $gh = $match->goals_h;
                $gv = $match->goals_v;
                if($gh === $gv){
                    $stats_h = [
                        "points" => 1,
                        "won" => 0,
                        "lost" => 0,
                        "draw" => 1,
                        "goals_for" => $gh,
                        "goals_against" => $gv,
                    ];
                    $stats_v = [
                        "points" => 1,
                        "won" => 0,
                        "lost" => 0,
                        "draw" => 1,
                        "goals_for" => $gv,
                        "goals_against" => $gh,
                    ];
                }else if($gh > $gv){
                    $stats_h = [
                        "points" => 3,
                        "won" => 1,
                        "lost" => 0,
                        "draw" => 0,
                        "goals_for" => $gh,
                        "goals_against" => $gv,
                    ];
                    $stats_v = [
                        "points" => 0,
                        "won" => 0,
                        "lost" => 1,
                        "draw" => 0,
                        "goals_for" => $gv,
                        "goals_against" => $gh,
                    ];
                }else if($gv > $gh){
                    $stats_h = [
                        "points" => 0,
                        "won" => 0,
                        "lost" => 1,
                        "draw" => 0,
                        "goals_for" => $gh,
                        "goals_against" => $gv,
                    ];
                    $stats_v = [
                        "points" => 3,
                        "won" => 1,
                        "lost" => 0,
                        "draw" => 0,
                        "goals_for" => $gv,
                        "goals_against" => $gh,
                    ];
                }

                foreach($stats_h as $sts_k => $sts_v){
                    $classification[$match->team_h][$sts_k] 
                        = isset($classification[$match->team_h][$sts_k]) 
                        ? $classification[$match->team_h][$sts_k] + $sts_v
                        : $sts_v;
                }
                $classification[$match->team_h]["goals_difference"]
                    = $classification[$match->team_h]["goals_for"] - $classification[$match->team_h]["goals_against"];

                $classification[$match->team_h]["matches"]
                    = isset($classification[$match->team_h]["matches"]) 
                    ? $classification[$match->team_h]["matches"] + 1
                    : 1;

                foreach($stats_v as $sts_k => $sts_v){
                    $classification[$match->team_v][$sts_k] 
                        = isset($classification[$match->team_v][$sts_k]) 
                        ? $classification[$match->team_v][$sts_k] + $sts_v
                        : $sts_v;
                    
                }
                $classification[$match->team_v]["goals_difference"]
                = $classification[$match->team_v]["goals_for"] - $classification[$match->team_v]["goals_against"];
                
                $classification[$match->team_v]["matches"]
                    = isset($classification[$match->team_v]["matches"]) 
                    ? $classification[$match->team_v]["matches"] + 1
                    : 1;
            }

            usort($classification, fn($a, $b) => $b['won'] <=> $a['won']);
            usort($classification, fn($a, $b) => $b['goals_for'] <=> $a['goals_for']);
            usort($classification, fn($a, $b) => $b['points'] <=> $a['points']);
            return response()->json($classification, 200);
        } catch (\Exception $err) {
            return response()->json([
                "error" => [
                    "message" => "Sorry, an error occurred",
                    "details" => "Code: " . $err->getCode() ." => " . $err->getMessage()
                ]
            ], 500);
        }
    }

    

}
