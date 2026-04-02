<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['numero', 'statut'];

public function reservations() {
    return $this->hasMany(Reservation::class);
}
}
