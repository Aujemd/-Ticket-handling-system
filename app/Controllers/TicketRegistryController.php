<?php

namespace App\Controllers;

use App\Models\{Ticket};
use App\Models\{User};
use Illuminate\Http\Request;

use Laminas\Diactoros\Response\RedirectResponse;

class TicketRegistryController extends BaseController{

    public function getTicketRegistryAction($request){

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            
            try{
                $postData = $request->getParsedBody();
                $ticket = new Ticket();
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
        return $this->renderHTML('tickets/ticketCreate.twig', [
            'url' => getenv('BASE_URL'),
        ]);
    }
}