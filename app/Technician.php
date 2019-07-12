<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $fillable = ['name', 'login', 'password'];

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function warnings() {
        return $this->hasMany(Warnings::class);
    }

    public function tickets() {
        return $this->belongsToMany(Ticket::class);
    }
}
