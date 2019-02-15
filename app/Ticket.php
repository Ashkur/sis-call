<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'description',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
