<?php

namespace App\Controllers;
use App\Models\{Ticket, User};

class DashboardController extends BaseController{
    public function getDashboardUserAction(){
        $user = User::where('id', $_SESSION['userId'])->first();
        return $this->renderHTML('tickets/ticketCreate.twig', [
            'url' => getenv('BASE_URL'),
            'user' => $user,
            'urlInit' => getenv('URL_INIT_USER'),
        ]);
    }

    public function getDashboardAdminAction(){
        $tickets = Ticket::all();
        $user = User::where('id', $_SESSION['userId'])->first();
        return $this->renderHTML('admins/dashboard.twig', [
            'tickets' => $tickets,
            'url' => getenv('BASE_URL'),
            'user' => $user,
            'urlInit' => getenv('URL_INIT_ADMIN'),
        ]);
    }
}