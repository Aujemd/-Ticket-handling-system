<?php

namespace App\Controllers;

use App\Models\{User};
use Laminas\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController {

    public function getLoginAction($request){

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $user = User::where('user', $postData['user'])->first();

            if($user){
                if(\password_verify($postData['password'], $user->password)){
                    $_SESSION['userId'] = $user->id;
                    return new RedirectResponse(getenv('BASE_URL').'Dashboard');
                }
            }
        }

        return $this->renderHTML('login.twig',[
            'url' => getenv('BASE_URL'),
        ]);
    }

    public function getLogout(){
        unset($_SESSION['userId']);
        return new RedirectResponse(getenv('BASE_URL').'Login');
    }
}