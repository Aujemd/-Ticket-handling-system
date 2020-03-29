<?php

namespace App\Controllers;

use App\Models\{Ticket, User};
use Laminas\Diactoros\Response\RedirectResponse;

class TicketsController extends BaseController{

    public function getTicketsAction($request){
        $params = $request->getQueryParams();
        $ticket = Ticket::findOrFail($params['id']);
        $user = User::where('id', $_SESSION['userId'])->first();

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
           
            try{
                $postData = $request->getParsedBody();
                $ticket->serial = $postData['serial'];
                $ticket->eventName = $postData['eventName'];
                $ticket->date = $postData['date'];
                $ticket->ubication = $postData['ubication'];
                $ticket->save();
                return $this->renderHTML('tickets/ticketShow.twig', [
                   'url' => getenv('BASE_URL').'Dashboard/Admin',
                   'ticket' => $ticket,
                   'user' => $user,
                   'urlInit' => getenv('URL_INIT_ADMIN'),

               ]);
            }catch(\Exception $e){
                var_dump($e->m);
            }
        }

        
        if($request->getUri()->getPath() == getenv('BASE_URL').'Delete/Ticket'){
            $ticket->delete();
            return new RedirectResponse(getenv('BASE_URL').'Dashboard/Admin');
        }

        if($request->getUri()->getPath() == getenv('BASE_URL').'Edit/Ticket'){
            return $this->renderHTML('tickets/ticketEdit.twig', [
                'url' => getenv('BASE_URL'),
                'ticket' => $ticket,
                'user' => $user,
                'urlInit' => getenv('URL_INIT_ADMIN'),
            ]);
        }


        if($user){
            if($user->admin == true){
                return $this->renderHTML('tickets/ticketShow.twig', [
                    'url' => getenv('BASE_URL').'Dashboard/Admin',
                    'ticket' => $ticket,
                    'user' => $user,
                    'urlInit' => getenv('URL_INIT_ADMIN'),
                ]);
            }
            
        }
        return $this->renderHTML('tickets/ticketShow.twig', [
            'url' => getenv('BASE_URL'),
            'ticket' => $ticket,
            'user' => $user,
            'urlInit' => getenv('URL_INIT_USER'),
        ]);
    }
}