<?php

namespace App\Controllers;
use App\Models\{User, Event};
use Laminas\Diactoros\Response\RedirectResponse;


class AdminController extends BaseController{

    public function getCreateEventAction(){
        $user = User::where('id', $_SESSION['userId'])->first();
        return $this->renderHTML('admins/eventCreate.twig', [
            'url' => getenv('BASE_URL'),
            'user' => $user,
        ]);
    }

    public function getSaveEventAction($request){
        $postData = $request->getParsedBody();
        $event  = new Event();
            try{
                $postData = $request->getParsedBody();
                $event->eventName = $postData['eventName'];
                $event->altos = $postData['altos'];
                $event->medios = $postData['medios'];
                $event->vip = $postData['vip'];
                $event->platino = $postData['platino'];
                $event->save();
                return new RedirectResponse(getenv('BASE_URL').'Dashboard/Admin');
            }catch(\Exception $e){
                var_dump($e->m);
            }
        
    }
}