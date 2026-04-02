<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['user_id', 'place_id', 'date_debut', 'date_fin_prevue', 'date_fin_reelle'];

public function user() {
    return $this->belongsTo(User::class);
}

public function place() {
    return $this->belongsTo(Place::class);
}
}
