<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\UserResource;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return UserResource::collection(User::all());
    }
}
