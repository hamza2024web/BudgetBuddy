<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependence extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'dependence_tag', 'dependence_id', 'tag_id');
    }
}
