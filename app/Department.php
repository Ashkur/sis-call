<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];
    
    public function employees() {
        return $this->hasMany(Employee::class);
    }

    public function technicians() {
        return $this->hasMany(Technician::class);
    }
}
