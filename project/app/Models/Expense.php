<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',    
        'group_id',  
        'total_amount', 
        'split_method' 
    ];

    public function group(){
        return $this->belongsTo(Group::class);
    }
    public function payers()
    {
        return $this->belongsToMany(User::class, 'expense_payers')->withPivot('amount_paid');
    }
    public function participants()
    {
        return $this->belongsToMany(User::class, 'expense_participants')->withPivot('amount_owed'); 
    }
}
