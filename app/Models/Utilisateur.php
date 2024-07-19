<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    protected $fillable =['nom','prenom','email','password','photo','departement','adresse','telephone'];

}
