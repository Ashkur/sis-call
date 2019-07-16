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

    public function technician() {
        return $this->belongsToMany(Technician::class);
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
