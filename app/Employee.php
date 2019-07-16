<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'login', 'password'];

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
