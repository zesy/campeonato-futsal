<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TeamMatch;
use App\Models\Team;

class TeamMatchController extends Controller
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
            $matches = TeamMatch::all();
            if(count($matches) < 1)
                return response()->json([
                        "message" => "No registered matches"
                    ], 200);
    
            return response($matches, 200);
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

            if($request->team_h === $request->team_v)
                return response()->json([
                    "error" => [
                        "message" => "Bad Request",
                        "details" => "Home Team and Visitor Team cannot be the same"
                    ]
                ], 400);

            if(!Team::where('id', $request->team_h)->exists()){
                return response()->json([
                    "error" => [
                        "message" => "Resource not found",
                        "details" => "Home Team with id $request->team_h not found"
                    ]
                ], 404);
            }
            if(!Team::where('id', $request->team_v)->exists()){
                return response()->json([
                    "error" => [
                        "message" => "Resource not found",
                        "details" => "Visitor Team with id $request->team_v not found"
                    ]
                ], 404);
            }

            $matches = new TeamMatch();
            $matches->match_date = $request->match_date;
            $matches->start_at = $request->start_at;
            $matches->end_at = $request->end_at;
            $matches->team_h = $request->team_h;
            $matches->team_v = $request->team_v;
            $matches->goals_h = $request->goals_h;
            $matches->goals_v = $request->goals_v;
            $saved = $matches->save();

            if(!$saved) return $this->sendMsgError("Something wrong when store new team match on database");
    
            return response()->json([
                    "message" => "Match Home Team (id: $request->team_h) x Visitor Team (id: $request->team_v) has been created"
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
            $matches = TeamMatch::find($id);
            if(!$matches) return response()->json([
                "error" => [
                    "message" => "Resource not found",
                    "details" => "Match with id $id not found"
                ]
                ], 404);
    
            return response()->json($matches, 200);
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
            $match = TeamMatch::find($id);
            if(!$match)
                return response()->json([
                    "error" => [
                        "message" => "Resource not found",
                        "details" => "Match with id $id not found"
                    ]
                ], 404);

            $match->fill($request->all());
            $match->save();
    
            return response()->json([
                "message" => "Match with id $id has been updated"
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
            if(TeamMatch::where('id', $id)->exists()) {
                $match = TeamMatch::find($id);
                $match->delete();
        
                return response()->json([
                    "message" => "Match with id $id has been deleted"
                ], 202);
            } else {
                return response()->json([
                    "error" => [
                        "message" => "Resource not found",
                        "details" => "Match with id $id not found"
                    ]
                ], 404);
            }
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }
}
