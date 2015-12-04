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
    
    public function login(Request $request)
    {
        $email      = $request->email;
        $password   = $request->password;

        if($email !="" && $password !="")
        {
            $whereThese = ['email' => $email, 'password' => md5($password)];
            $user = User::where($whereThese);
            $userCount = $user->count();
            
            $code = str_random(64);
                    
            if($userCount > 0) {
                $where = ['email' => $email];
                $user = User::where($where)->update(array('remember_token' => $code));                        
            }

            $res = ($userCount > 0) ? array('status' => 1,'error-code'=>0, 'message' => "User Found",'token' => $code) : array('status' => 0,'error-code'=>201, 'message' => "Sorry! User not found");
            return response()->json($res);

        }else if($email ==""){
            $res =  array('status' => 0,'error-code'=>202, 'message' => "Email is empty") ;
            return response()->json($res);

        }else if($password ==""){
            $res =  array('status' => 0,'error-code'=>203, 'message' => "Password is empty") ;
            return response()->json($res);
        }
    }

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

            $australian_phone = "/^(\+\d{2}[ \-]{0,1}){0,1}(((\({0,1}[ \-]{0,1})0{0,1}\){0,1}[2|3|7|8]{1}\){0,1}[ \-]*(\d{4}[ \-]{0,1}\d{4}))|(1[ \-]{0,1}(300|800|900|902)[ \-]{0,1}((\d{6})|(\d{3}[ \-]{0,1}\d{3})))|(13[ \-]{0,1}([\d \-]{5})|((\({0,1}[ \-]{0,1})0{0,1}\){0,1}4{1}[\d \-]{8,10})))$/";
            if (!preg_match($australian_phone,$phone)) 
            {
              $res = array('status' => 0,'error-code'=>109, 'message' => "Mobile is not Australian phone no.");
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
    
    public function logout(Request $request)
    { 
        $where = ['email' => $request->email];
        $user = User::where($where);
        $userCount = $user->count();        
                
        if($userCount > 0) {
            $user = User::where($where)->update(array('remember_token' => NULL)); 
            $res =  array('status' => 1,'error-code'=>200, 'message' => "user successfully logout") ;
            return response()->json($res);                       
        
        } else {
            $res =  array('status' => 0,'error-code'=>201, 'message' => "Sorry! User not found");
            return response()->json($res);
        }
        
    }  


}
