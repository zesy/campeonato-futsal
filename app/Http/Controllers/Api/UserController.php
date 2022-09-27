<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
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
            $users = User::all();
            if(count($users) < 1)
                return response()->json([
                        "message" => "No registered users"
                    ], 200);
    
            return response()->json($users, 200);
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
    public function register(Request $request){
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);

            $saved = $user->save();

            if(!$saved) return $this->sendMsgError("Something wrong when store new user on database");

            return response()->json([
                "message" => "User '$user->name' has been created"
            ], 201);
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

    /**
     * Auth user
     * 
     * @param \Illuminate\Http\Request  $request
     */
    public function signin(Request $request){
        try {
            $credentials = $request->only(['email', 'password']);

            if(!auth()->attempt($credentials))
                return response()->json([
                    "message" => "Your user or password is invalid"
                ], 401);

            $token = auth()->user()->createToken('auth_token');

            return response()->json([
                "data" => [
                    "message" => "Sign in successfully",
                    "token" => $token->plainTextToken
                ]
            ], 200);

        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }

    public function logout(){
        try {
            auth()->user()->currentAccessToken()->delete();

            return response()->json([
                "message" => "Logout successfully"
            ], 200);
        } catch (\Exception $err) {
            return $this->sendError($err);
        }
    }
}
