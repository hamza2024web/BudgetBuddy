<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'name', 'amount', 'category', 'next_due_date'
    ];
}
