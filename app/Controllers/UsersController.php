<?php

namespace App\Controllers;
use App\Models\{User};
use Laminas\Diactoros\Response\RedirectResponse;

class UsersController extends BaseController{

    public function getUsersAction($request){

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
                $user->save();
                
                return new RedirectResponse(getenv('BASE_URL').'Dashboard');
            }catch(\Exception $e){
                var_dump($e->m);
            }
        }
        return $this->renderHTML('signUp.twig', [
            'url' => getenv('BASE_URL'),
        ]);
    }
}