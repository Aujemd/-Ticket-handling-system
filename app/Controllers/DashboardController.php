<?php

namespace App\Controllers;
use App\Models\{Ticket};

class DashboardController extends BaseController{
    public function getDashboardAction(){

        $tickets = Ticket::all();
        return $this->renderHTML('dashboard.twig', [
            'tickets' => $tickets,
            'url' => getenv('BASE_URL'),
        ]);
    }
}