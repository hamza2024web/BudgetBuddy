<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'tags',
    ];

    public function dependences (){
        return $this->belongsToMany(Dependence::class,'dependence_tag', 'tag_id', 'dependence_id');
    }
}
