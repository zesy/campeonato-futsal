<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Player;

class PlayerController extends Controller
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
            $players = Player::all();
            if(count($players) < 1)
                return response()->json([
                        "message" => "No registered players"
                    ], 200);
    
            return response()->json($players, 200);
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
            $player = new Player();
            $player->name = $request->name;
            $saved = $player->save();
    
            if(!$saved) return $this->sendMsgError("Something wrong when store new player on database");

            return response()->json([
                    "message" => "Player '$request->name' has been created"
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
            $players = Player::find($id);
            if(!$players) return response()->json([
                "error" => [
                    "message" => "Resource not found",
                    "details" => "Player with id $id not found"
                ]
                ], 404);
    
            return response()->json($players, 200);
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
            $player = Player::find($id);
            if(!$player)
                return response()->json([
                    "error" => [
                        "message" => "Resource not found",
                        "details" => "Player with id $id not found"
                    ]
                ], 404);

            $name = $player->name;
            $player->fill($request->all());
            $player->save();
    
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
            if(Player::where('id', $id)->exists()) {
                $player = Player::find($id);
                $name = $player->name;
                $player->delete();
        
                return response()->json([
                    "message" => "Player '$name' has been deleted"
                ], 202);
            } else {
                return response()->json([
                    "error" => [
                        "message" => "Resource not found",
                        "details" => "Player with id $id not found"
                    ]
                ], 404);
            }
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }
}
