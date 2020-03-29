<?php

namespace App\Controllers;

use App\Models\{User};
use Laminas\Diactoros\Response\RedirectResponse;

class UsersController extends BaseController{
    public function getUsersSignUpAction($request){
        if($request->getMethod() == 'POST'){
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
                return new RedirectResponse(getenv('BASE_URL').'Dashboard/User');
            }catch(\Exception $e){
                var_dump($e->m);
            }
        }
        return $this->renderHTML('users/signUp.twig', [
            'url' => getenv('BASE_URL'),
        ]);
    }

    public function getUsersLoginAction($request){
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $user = User::where('user', $postData['user'])->first();
            if($user){
                if($user->admin == true){
                    if($postData['password'] == $user->password){
                        $_SESSION['userId'] = $user->id;
                        return new RedirectResponse(getenv('BASE_URL').'Dashboard/Admin');
                    }
                }
                if(\password_verify($postData['password'], $user->password)){
                    $_SESSION['userId'] = $user->id;
                    return new RedirectResponse(getenv('BASE_URL').'Dashboard/User');
                }
            }
        }
        return $this->renderHTML('users/login.twig',[
            'url' => getenv('BASE_URL'),
        ]); 
    }

    public function getUsersLogoutAction($request){
        unset($_SESSION['userId']);
        return new RedirectResponse(getenv('BASE_URL').'Login');
    }
}