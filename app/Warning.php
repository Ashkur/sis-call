<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    protected $fillable = ['title', 'description'];

    public function technician() {
        return $this->belongsTo(Technician::class);
    }
}
