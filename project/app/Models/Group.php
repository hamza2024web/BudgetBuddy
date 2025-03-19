<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom', 'devise', 'isAdmin', 'depense', 
        'montant', 'payeur_id', 'somme', 'methode_somme'
    ];

    public function users(){
        return $this->belongsToMany(User::class,'user_group');
    }
    public function payeurs(){
        return $this->belongsToMany(User::class,'group_payeurs');
    }
}
