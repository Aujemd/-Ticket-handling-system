<?php

namespace App\Controllers;
use App\Models\{Ticket, User, Event};

class DashboardController extends BaseController{

    public function getDashboardUserAction($request){

        function generarCodigo($longitud) {
            $key = '';
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
            $max = strlen($pattern)-1;
            for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
            return $key;
        }

        $user = User::where('id', $_SESSION['userId'])->first();
        $events = Event::all();
        
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $event = Event::where('id', $postData['eventId'])->first();
            return $this->renderHTML('tickets/ticketCreate.twig', [
                'url' => getenv('BASE_URL'),
                'user' => $user,
                'urlInit' => getenv('URL_INIT_USER'),
                'event' => $event,
                'serial' => generarCodigo(40), 
            ]);

        }
        return $this->renderHTML('tickets/chooseEvent.twig', [
            'url' => getenv('BASE_URL'),
            'user' => $user,
            'urlInit' => getenv('URL_INIT_USER'),
            'events' => $events,
        ]);
    }

    public function getDashboardAdminAction($request){

        $params = $request->getQueryParams();
        
        $events = Event::latest()->take(4)->get();

        $tickets = Ticket::all();

        if(isset($params['eventName'])){
            $event = Event::where('eventName', $params['eventName'])->first();
            $tickets = $event->tickets;
        }
        
        $user = User::where('id', $_SESSION['userId'])->first();
        return $this->renderHTML('admins/dashboard.twig', [
            'tickets' => $tickets,
            'url' => getenv('BASE_URL'),
            'user' => $user,
            'urlInit' => getenv('URL_INIT_ADMIN'),
            'events' => $events,
        ]);
    }
}