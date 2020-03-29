<?php

namespace App\Controllers;
use App\Models\{Ticket};

class DashboardController extends BaseController{
    public function getDashboardUserAction(){
        return $this->renderHTML('users/dashboard.twig', [
            'url' => getenv('BASE_URL'),
        ]);
    }

    public function getDashboardAdminAction(){
        $tickets = Ticket::all();
        return $this->renderHTML('admins/dashboard.twig', [
            'tickets' => $tickets,
            'url' => getenv('BASE_URL'),
        ]);
    }
}