<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use function Pest\Laravel\json;

class AuthController extends Controller
{
    use ApiResponses;
    public function login(ApiLoginRequest $request)
    {
        return $this->ok($request->get('email'));
    }

    public function register()
    {
        return $this->ok('Registered successfully!');
    }
}
