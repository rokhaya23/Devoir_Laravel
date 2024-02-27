<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class Utilisateur extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable =['nom','prenom','email','password','photo'];

}
