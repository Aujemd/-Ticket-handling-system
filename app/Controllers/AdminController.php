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
        $user = User::where('id', $_SESSION['userId'])->first();
        $postData = $request->getParsedBody();
        $event  = new Event();
            try{
                $postData = $request->getParsedBody();
                $event->eventName = $postData['eventName'];
                $event->altos = $postData['altos'];
                $event->medios = $postData['medios'];
                $event->vip = $postData['vip'];
                $event->platino = $postData['platino'];
                $event->date = $postData['date'];
                $event->save();
                return $this->renderHTML('admins/showEvent.twig', [
                    'url' => getenv('BASE_URL').'Dashboard/Admin',
                    'event' => $event,
                    'user' => $user,
                    'urlInit' => getenv('URL_INIT_ADMIN'),
                ]);
                return new RedirectResponse(getenv('BASE_URL').'Dashboard/Admin');
            }catch(\Exception $e){
                var_dump($e->m);
            }
        
    }
}