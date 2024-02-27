<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Events\UserCreated;
use App\Jobs\CreateUserJob;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;


class UserController extends Controller
{
    //

    protected $user;
    public function __construct()
    {
        $this->result = (object)array(
            'status' => false,
            'status_code' => 200,
            'message' => null,
            'data' => (object) null,
            'token' => null,
            'debug' => null
        );
    }

    public function createUser(Request $request){
        //validate request data

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            
        ]); 
        
        if ($validator->fails()) {
            $this->result->status = false;
            $this->result->message = "Sorry a Validation Error Occured";
            $this->result->data->errors = $validator->errors()->all();
            $this->result->status_code = 422;
            return response()->json($this->result, 422);
        }

        // $input['first_name'] = $request['firstName'];
        $input['name'] = $request['firstName'].' '.$request['lastName'];
        $input['email'] = $request['email'];
        $create_user = User::create($input);//insert user data into database

        // Dispatch UserCreated Event
        event(new UserCreated($create_user));
        // CreateUserJob::dispatch($create_user);


        $this->result->status = true;
        $this->result->message = "User created successfully.";
        $this->result->data->user = $create_user;
        $this->result->status_code = 200;
        return response()->json($this->result, 200);

        


    }

}
