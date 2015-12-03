<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Http\Requests;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /**
     * Registeration of new user.
     * 
     * @return \Illuminate\Http\Response
     */
    

    public function register(Request $request)
    {
         $name       = $request->name;
        $category   = $request->category;
        $email      = $request->email;
        $phone      = $request->phone;
        $password   = $request->password;

        if($name !="" && $category !="" && $email !="" && $phone !="" && $password !="")
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $res = array('status' => 0,'error-code'=>108, 'message' => "Invalid email format");
              return response()->json($res);
            } 

            $users = User::where('email', $email);
            if($users->count() > 0) {
                $res = array('status' => 0,'error-code'=>101, 'message' => "user for this email already exists");
                return response()->json($res);
            }

            $users = User::where('phone', $phone);
            if($users->count() > 0) {
                $res = array('status' => 0,'error-code'=>102, 'message' => "user for this Mobile no. already exists");
                return response()->json($res);
            }

            $user = new User;
            $user->name     = $name;
            $user->category = $category;
            $user->email    = $email;
            $user->phone    = $phone;
            $user->password = md5($password);

            $res = ($user->save()) ? array('status' => 1,'error-code'=>0, 'message' => "user successfully added") : array('status' => 0, 'error-code'=>100, 'message' => "Sorry! There was an error in saving the user");
            return response()->json($res);
        }else if($name ==""){
            $res =  array('status' => 0,'error-code'=>103, 'message' => "Display Name is empty") ;
            return response()->json($res);
        }else if($category ==""){
            $res =  array('status' => 0,'error-code'=>104, 'message' => "Category is empty") ;
            return response()->json($res);
        }else if($email ==""){
            $res =  array('status' => 0,'error-code'=>105, 'message' => "Email is empty") ;
            return response()->json($res);
        }else if($phone ==""){
            $res =  array('status' => 0,'error-code'=>106, 'message' => "Mobile is empty") ;
            return response()->json($res);
        }else if($password ==""){
            $res =  array('status' => 0,'error-code'=>107, 'message' => "Password is empty") ;
            return response()->json($res);
        }
    }
}
