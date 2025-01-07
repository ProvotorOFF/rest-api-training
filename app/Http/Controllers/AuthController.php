<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store(LoginRequest $request) {
        $token = $request->authenticate();
        return ['token' => $token];
    }
}
