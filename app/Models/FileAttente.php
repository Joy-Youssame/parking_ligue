<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileAttente extends Model
{
    use HasFactory;

    // AJOUTE CETTE LIGNE :
    protected $fillable = ['user_id', 'rang'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}