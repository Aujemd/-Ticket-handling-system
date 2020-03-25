<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model{
    protected $table = 'users';


    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}