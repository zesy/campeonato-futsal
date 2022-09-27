<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Team;
use App\Models\Player;

class TeamController extends Controller
{
    private function sendError($err){
        return response()->json([
            "error" => [
                "message" => "Sorry, an error occurred",
                "details" => "Code: " . $err->getCode() ." => " . $err->getMessage()
            ]
        ], 500);
    }
    private function sendMsgError($msg){
        return response()->json([
            "error" => [
                "message" => "Sorry, an error occurred",
                "details" => $msg
            ]
        ], 500);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectAll(){
        try {
            $teams = Team::all();
            if(count($teams) < 1)
                return response()->json([
                        "message" => "No registered players"
                    ], 200);
    
            return response()->json($teams, 200);
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request){
        try {
            $team = new Team();
            $team->name = $request->name;
            $saved = $team->save();

            if(!$saved) return $this->sendMsgError("Something wrong when store new team on database");
    
            return response()->json([
                    "message" => "Team '$request->name' has been created"
                ], 201);
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function selectOne($id){
        try {
            $teams = Team::find($id);
            if(!$teams) return response()->json([
                "error" => [
                    "message" => "Resource not found",
                    "details" => "Team with id $id not found"
                ]
            ], 404);

            $currPlayer = [];
            if(Player::where('team_id', $teams->id)->exists()){
                $currPlayer = Player::where('team_id', $teams->id)
                                    ->get(["id", "name", "number"]);
            }
            $teams['players'] = $currPlayer;
    
            return response()->json($teams, 200);
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        try {
            $team = Team::find($id);
            if(!$team)
                return response()->json([
                    "message" => "Team not found",
                ], 404);

            $name = $team->name;
            $team->fill($request->all());
            $team->save();
    
            return response()->json([
                "message" => "Team '$name' has been updated"
            ], 200);
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id){
        try {
            if(Team::where('id', $id)->exists()) {
                $team = Team::find($id);
                $name = $team->name;
                $team->delete();
        
                return response()->json([
                    "message" => "Team '$name' has been deleted"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Team not found"
                ], 404);
            }
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $idp
     * @return \Illuminate\Http\Response
     */
    public function addPlayer(Request $request, $id, $pid){
        try {
            $player = Player::find($pid);
            if($player->team_id != null)
                return response()->json([
                    "error" => [
                        "message" => "Resource conflict",
                        "details" => "The player with id $pid already in team with id $player->team_id"
                    ]
                ], 409);

            $alreadyHasNumber = Player::where('team_id', $id)
                                    ->where('number', $request->number)
                                    ->get();

            if(count($alreadyHasNumber) > 0)
                return response()->json([
                    "error" => [
                        "message" => "Resource conflict",
                        "details" => "The team with id $id already have a player with number $request->number"
                    ]
                ], 409);

            $team = Team::find($id);
            if(!$team || !$player)
                return response()->json([
                    "error" => [
                        "message" => "Resource not found",
                        "details" => (!$team ? "Team with id $id" : "Player with id $pid") . " not found"

                    ]
                ], 404);

            $t_name = $team->name;
            $p_name = $player->name;
            $player->fill([
                "number" => $request->number,
                "team_id" => $id
            ]);
            $player->save();
    
            return response()->json([
                "message" => "Player '$p_name' has been added to team '$t_name'"
            ], 200);
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  int  $id_player
     * @return \Illuminate\Http\Response
     */
    public function removePlayer($id, $pid){
        try {
            $player = Player::find($pid);

            if(!$player) return response()->json([
                    "error" => [
                        "message" => "Resource not found",
                        "details" => "Player with id $pid not found"
                    ]
                ], 404);

            if($player->team_id != $id) return response()->json([
                "error" => [
                    "message" => "Resource not found",
                    "details" => "Player with id $pid doesn't belongs to team with id $id"
                ]
            ], 404);

            $player->fill([
                'number' => null,
                'team_id' => null
            ]);

            $player->save();

            return response()->json([
                "message" => "Player with id $pid has been removed from team with id $id"
            ], 200);
            
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

}
