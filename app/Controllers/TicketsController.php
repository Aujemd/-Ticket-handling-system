<?php

namespace App\Controllers;

use App\Models\{Ticket};
use Laminas\Diactoros\Response\RedirectResponse;

class TicketsController extends BaseController{

    public function getTicketsAction($request){
        $params = $request->getQueryParams();
        $ticket = Ticket::findOrFail($params['id']);

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
           
            try{
                $postData = $request->getParsedBody();
                $ticket->serial = $postData['serial'];
                $ticket->eventName = $postData['eventName'];
                $ticket->date = $postData['date'];
                $ticket->ubication = $postData['ubication'];
                $ticket->user_id = $_SESSION['userId'];
                $ticket->save();
                return $this->renderHTML('tickets/ticketShow.twig', [
                   'url' => getenv('BASE_URL'),
                   'ticket' => $ticket,
               ]);
            }catch(\Exception $e){
                var_dump($e->m);
            }
        }

        
        if($request->getUri()->getPath() == getenv('BASE_URL').'Delete/Ticket'){
            $ticket->delete();
            return new RedirectResponse(getenv('BASE_URL').'Dashboard');
        }

        if($request->getUri()->getPath() == getenv('BASE_URL').'Edit/Ticket'){
            return $this->renderHTML('tickets/ticketEdit.twig', [
                'url' => getenv('BASE_URL'),
                'ticket' => $ticket,
            ]);
        }

        return $this->renderHTML('tickets/ticketShow.twig', [
            'url' => getenv('BASE_URL'),
            'ticket' => $ticket,
        ]);
    }
}