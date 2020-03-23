<?php

namespace App\Controllers;

class DashboardController extends BaseController{
    public function getDashboardAction(){
        return $this->renderHTML('dashboard.twig');
    }
}