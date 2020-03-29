<?php

namespace App\Controllers;
use App\Models\{User};


class AdminController extends BaseController{

    public function getCreateEventAction(){
        $user = User::where('id', $_SESSION['userId'])->first();
        return $this->renderHTML('admins/eventCreate.twig', [
            'url' => getenv('BASE_URL'),
            'user' => $user,
        ]);
    }
}