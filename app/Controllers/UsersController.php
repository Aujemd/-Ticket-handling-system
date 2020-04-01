<?php

namespace App\Controllers;

use App\Models\{User};
use Laminas\Diactoros\Response\RedirectResponse;

class UsersController extends BaseController{
    public function getUsersSignUpAction($request){
            $postData = $request->getParsedBody();
            try{
                $postData = $request->getParsedBody();
                $user = new User();
                $user->names = $postData['names'];
                $user->lastnames = $postData['lastnames'];
                $user->vid = $postData['vid'];
                $user->address = $postData['address'];
                $user->sex = $postData['sex'];
                $user->contact = $postData['contact'];
                $user->email = $postData['email'];
                $user->user = $postData['user'];
                $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
                $user->admin = false;
                $user->save();
                $_SESSION['userId'] = $user->id;
                return new RedirectResponse(getenv('BASE_URL').'Dashboard/User');
            }catch(\Exception $e){
                var_dump($e->getMessage());
            } 
    }

    public function getUsersLoginAction($request){
            $postData = $request->getParsedBody();
            $user = User::where('user', $postData['user'])->first();
            if($user){
                if($user->admin == true){
                    if($postData['password'] == $user->password){
                        $_SESSION['userId'] = $user->id;
                        return new RedirectResponse(getenv('BASE_URL').'Dashboard/Admin');
                    }else{
                        return new RedirectResponse(getenv('BASE_URL'));

                    }
                }
                if(\password_verify($postData['password'], $user->password)){
                    $_SESSION['userId'] = $user->id;
                    return new RedirectResponse(getenv('BASE_URL').'Dashboard/User');
                }else{
                    return new RedirectResponse(getenv('BASE_URL'));
                }
            }else{
                return new RedirectResponse(getenv('BASE_URL'));
            }
    }

    public function getUsersLogoutAction($request){
        unset($_SESSION['userId']);
        return new RedirectResponse(getenv('BASE_URL'));
    }
}